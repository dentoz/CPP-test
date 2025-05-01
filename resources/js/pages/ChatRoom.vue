<template>
    <v-app>
        <div class="dashboard" ref="showSidebar">
            <Header @sidebarHandler="toggleSidebar"></Header>
            <section class="main-content">
                <h2>Chat Room</h2>
                <div class="chatroom-action">
                    <v-btn>
                        <div class="chatroom-item create">
                            <div class="chatroom-icon">
                                <v-icon size="40" color="#f8f9fa">mdi-plus</v-icon>
                            </div>
                            <div class="statistic-text py-2">
                                <p>Create Chatroom</p>
                            </div>
                        </div>
                    </v-btn>
                    <v-btn>
                        <div class="chatroom-item chat">
                            <div class="chatroom-icon">
                                <v-icon size="40" color="#f8f9fa">mdi-chat</v-icon>
                            </div>
                            <div class="statistic-text py-2">
                                <p>Join Chatroom</p>
                            </div>
                        </div>
                    </v-btn>
                    <v-btn>
                        <div class="chatroom-item active-chatroom">
                            <div class="chatroom-icon">
                                <v-icon size="40" color="#f8f9fa">mdi-message-text</v-icon>
                            </div>
                            <div class="statistic-text py-2">
                                <p>My Active Chatroom</p>
                            </div>
                        </div>
                    </v-btn>
                </div>
            </section>
            <Sidebar :showSidebar="showSidebar"></Sidebar>
        </div>
    </v-app>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import Header from '../components/HeaderComponent.vue';
import Sidebar from '../components/sidebarComponent.vue';

const showSidebar = ref(false);
const toggleSidebar = (state) => {
    showSidebar.value = state;
};
const drawer = ref(null);

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

<style scoped>
.chatroom-action {
    display: flex;
    gap: 2rem;
    margin-top: 20px;
}

.chatroom-action .chatroom-item {
    display: flex;
    align-items: center;
    background-color: #343a40;
    color: #f8f9fa;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    min-width: 200px;
    min-height: 80px;
    gap: 1rem;
    padding-right: 1rem;
}

.chatroom-action .chatroom-item.create {
    background-color: #17a2b8;
}
.chatroom-action .chatroom-item.chat {
    background-color: #28a745;
}
.chatroom-action .chatroom-item.active-chatroom {
    background-color: #f39c12;
}

.chatroom-icon {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 60px;
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.v-btn, .v-btn:hover {
    background-color: transparent;
    border: none;
    box-shadow: unset;
    width: 250px;
}
</style>