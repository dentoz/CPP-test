<template>
  <v-app>
    <div class="dashboard" ref="showSidebar">
      <Header @sidebarHandler="toggleSidebar"></Header>
      <section class="main-content">
        <div v-if="!chatAttributes.topic || !chatAttributes.nickname || !chatAttributes.chatRoomUsers">
          <v-sheet>
            <v-card>
              <template v-slot:title>
                <span class="font-weight-black">To continue please fill the form</span>
              </template>
              <form @submit.prevent="onSubmit">
                <v-card-text class="bg-surface-light pt-4">
                  <v-text-field label="Nickname" v-model="nickname.value.value"
                    :error-messages="nickname.errorMessage.value"></v-text-field>
                  <v-text-field label="Topic" v-model="topic.value.value"
                    :error-messages="topic.errorMessage.value"></v-text-field>
                  <v-text-field label="Invite" v-model="chatRoomUsers.value.value"
                    :error-messages="chatRoomUsers.errorMessage.value"></v-text-field>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue-darken-1" type="submit" :disabled="loading">
                    <v-progress-circular v-if="loading" class="mr-2" indeterminate></v-progress-circular>
                    Submit
                  </v-btn>
                </v-card-actions>
              </form>
            </v-card>
          </v-sheet>
        </div>
        <div v-else>
          <h2>Chatroom - {{ chatAttributes.topic }}</h2>
          <v-row class="h-100" no-gutters>
            <!-- Sidebar: Users -->
            <v-col cols="3" class="pa-2">
              <v-card class="h-100">
                <v-card-title>Users</v-card-title>
                <v-card-text class="overflow-auto">
                  <v-list density="compact">
                    <v-list-item v-for="user in users" :key="user.id">
                      <v-list-item-title>{{ user.email }}</v-list-item-title>
                    </v-list-item>
                  </v-list>
                </v-card-text>
                <v-card-actions>
                  <v-btn color="error" @click="leaveChat">Leave Chat</v-btn>
                </v-card-actions>
              </v-card>
            </v-col>

            <!-- Main Chat Area -->
            <v-col cols="9" class="pa-2 d-flex flex-column h-100">
              <v-card class="flex-grow-1 overflow-auto">
                <v-card-text>
                  <div v-for="(msg, index) in messages" :key="index" class="mb-2">
                    <strong>{{ msg.user }}:</strong> {{ msg.message }}
                  </div>
                </v-card-text>
              </v-card>

              <v-card class="mt-2">
                <v-card-text>
                  <v-form @submit.prevent="sendMessage">
                    <v-text-field v-model="newMessage" placeholder="Type a message" outlined dense hide-details
                      @keydown.enter.prevent="sendMessage" />
                    <v-btn color="primary" @click="sendMessage">Send</v-btn>
                  </v-form>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </div>
      </section>
      <Sidebar :showSidebar="showSidebar"></Sidebar>
    </div>
  </v-app>
</template>

<script setup>
import { onMounted, ref, onUnmounted, watch, provide } from 'vue';
import Header from '../components/HeaderComponent.vue';
import Sidebar from '../components/sidebarComponent.vue';
import * as yup from 'yup';
import { useField, useForm } from 'vee-validate';
import { io } from 'socket.io-client';

const socket = io('http://localhost:3000'); // or your deployed URL

const userData = JSON.parse(localStorage.getItem('token') ?? '{}');
const showSidebar = ref(false);
const toggleSidebar = (state) => {
  showSidebar.value = state;
};
const drawer = ref(null);

const roomId = ref(0);
const loading = ref(false)
const messages = ref([]);
const newMessage = ref('');
const users = ref([]);
const chatAttributes = ref({
  topic: '',
  chatRoomUsers: '',
  nickname: userData.nick_name
})

provide('invitedUser', chatAttributes.value.chatRoomUsers);

const schema = yup.object().shape({
  nickname: yup.string().required('nick name is required').min(3, 'nick name must be at least 3 characters'),
  topic: yup.string().required('topic is required'),
  invite: yup.string().required('you must invite other user').email('not email format'),
})

const { handleSubmit, setFieldValue, values } = useForm({ validationSchema: schema });

setFieldValue('nickname', chatAttributes.value.nickname)
setFieldValue('topic', chatAttributes.value.topic)
setFieldValue('invite', chatAttributes.value.chatRoomUsers)

const nickname = useField('nickname', schema);
const topic = useField('topic', schema);
const chatRoomUsers = useField('invite', schema);

const createChatroom = async (values) => {
  const response = await fetch('/api/chat-rooms', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'authorization': `Bearer ${userData.token}`,
      // 'X-CSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
      ...values,
      google_id: userData.google_id
    }),
    credentials: 'include'
  });

  const data = await response.json();
  if (!response.ok) {
    loading.value = false;
    return alert(data.message);
  }

  return data.data
}

const inviteUser = async (data) => {
  const response = await fetch(`/api/chat-rooms/${data.id}/invite`, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'authorization': `Bearer ${userData.token}`,
      // 'X-XSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
      email: values.invite
    }),
    credentials: 'include'
  });

  const responseData = await response.json();
  if (!response.ok) {
    loading.value = false;
    return alert(responseData.message);
  }

  return responseData.data;
}

const onSubmit = handleSubmit(async (values) => {
  loading.value = true;
  const createdChatRoom = await createChatroom(values);
  const invitedUser = await inviteUser(createdChatRoom);
  users.value.push({ id: 1, email: values.invite })
  loading.value = false;

  if (invitedUser) {
    roomId.value = invitedUser.chat_room_id;
    chatAttributes.value = {
      topic: values.topic,
      chatRoomUsers: values.invite,
      nickname: values.nickname
    }
  }
});

const sendMessage = async () => {
  if (!newMessage.value) return;

  const response = await fetch(`/api/chat-rooms/${roomId.value}/messages`, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'authorization': `Bearer ${userData.token}`,
      // 'X-XSRF-TOKEN': csrfToken
    },
    body: JSON.stringify({
      message: newMessage.value
    }),
    credentials: 'include'
  });

  const responseData = await response.json();
  if (!response.ok) {
    loading.value = false;
    return alert(responseData.message);
  }

  newMessage.value = '';
};

const getMessages = async (roomId) => {
  const response = await fetch(`/api/chat-rooms/${roomId}/messages`, {
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'authorization': `Bearer ${userData.token}`,
      // 'X-XSRF-TOKEN': csrfToken
    },
    credentials: 'include'
  });

  const responseData = await response.json();
  if (!response.ok) {
    loading.value = false;
    return alert(responseData.message);
  }

  return responseData.data;
}

const handleClickOutside = (event) => {
  if (drawer.value && !drawer.value.contains(event.target)) {
    showSidebar.value = false;
  }
};

watch(() => roomId.value, async (newVal, oldVal) => {
  if (oldVal) {
    console.log('xxxxxxxxxggggggleave')
    socket.emit('leave-room', `chat.room.${oldVal}`);
  }
  if (newVal) {
    socket.emit('join-room', `chat.room.${newVal}`);
    const data = await getMessages(newVal)
    messages.value = data
  }
});

onMounted(async () => {
  document.addEventListener('click', handleClickOutside);
  socket.on('user-invited', (data) => {
    users.value.push({ id: users.value.length + 1, email: data.user })
  });
  socket.on('new-message', (data) => {
    console.log('xxxxxtestxxxxx', data)
    messages.value.push({ id: data.id, user: data.user, message: data.message })
  })
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
  socket.off('user-invited');
  socket.off('new-message');
});

</script>