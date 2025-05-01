<!-- <template>
    <div>
      <h1>Chat Room {{ roomId }}</h1>
      <div v-for="msg in messages" :key="msg.id">
        <strong>{{ msg.user.name }}</strong>: {{ msg.message }}
      </div>
      <input v-model="newMessage" @keyup.enter="sendMessage" placeholder="Type a message..." />
    </div>
  </template> -->

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
          
        </div>
      </section>
      <Sidebar :showSidebar="showSidebar"></Sidebar>
    </div>
  </v-app>
</template>

<script setup>
import { onMounted, ref, onUnmounted } from 'vue';
import { useRoute } from 'vue-router';
import axios from 'axios';
import Header from '../components/HeaderComponent.vue';
import Sidebar from '../components/sidebarComponent.vue';
import * as yup from 'yup';
import { useField, useForm } from 'vee-validate';

const userData = JSON.parse(localStorage.getItem('token') ?? '{}');
const showSidebar = ref(false);
const toggleSidebar = (state) => {
  showSidebar.value = state;
};
const drawer = ref(null);

const route = useRoute();
const roomId = route.query.id;
const loading = ref(false)
const messages = ref([]);
const newMessage = ref('');
const chatAttributes = ref({
  topic: '',
  chatRoomUsers: '',
  nickname: userData.nick_name
})

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
  loading.value = false;

  if (invitedUser) {
    chatAttributes.value = {
      topic: values.topic,
      chatRoomUsers: values.invite,
      nickname: values.nickname
    }
  }
});

onMounted(async () => {
  const res = await axios.get(`/api/chat-rooms/${roomId}/messages`, {
    headers: {
      'Content-Type': 'application/json',
      'authorization': `Bearer ${userData.token}`
    },
    credentials: 'include'
  });
  messages.value = res.data;

  window.Echo.join(`chat-room.${roomId}`)
    .listen('MessageSent', (e) => {
      messages.value.push(e);
    });
});

const sendMessage = async () => {
  if (!newMessage.value) return;
  await axios.post(`/chat-rooms/${roomId}/messages`, { message: newMessage.value });
  newMessage.value = '';
};

const handleClickOutside = (event) => {
  if (drawer.value && !drawer.value.contains(event.target)) {
    showSidebar.value = false;
  }
};

onMounted(async () => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

</script>