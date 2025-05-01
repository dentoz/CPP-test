<template>
    <v-app>
        <div class="dashboard" ref="showSidebar">
            <Header @sidebarHandler="toggleSidebar"></Header>
            <section class="main-content">
                <h2>Dashboard Chat Room</h2>
                <div class="statistics">
                    <div class="statistic-item">
                        <div class="statistic-icon total-users">
                            <v-icon size="40" color="#f8f9fa">mdi-account</v-icon>
                        </div>
                        <div class="statistic-text py-2">
                            <p>Total Registered User</p>
                            <h2>{{ dashboardData.totalRegisteredUser }}</h2>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon active-users">
                            <v-icon size="40" color="#f8f9fa">mdi-account-multiple</v-icon>
                        </div>
                        <div class="statistic-text py-2">
                            <p>Total Active User</p>
                            <h2>{{ dashboardData.totalAvtiveUser }}</h2>
                        </div>
                    </div>
                    <div class="statistic-item">
                        <div class="statistic-icon active-chatroom">
                            <v-icon size="40" color="#f8f9fa">mdi-message</v-icon>
                        </div>
                        <div class="statistic-text py-2">
                            <p>Total Active Chatroom</p>
                            <h2>{{ dashboardData.totalActiveChatRoom }}</h2>
                        </div>
                    </div>

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

const dashboardData = ref({
    totalRegisteredUser: 0,
    totalAvtiveUser: 0,
    totalActiveChatRoom: 0
});
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

const getDashboardData = async () => {
    const userData = JSON.parse(localStorage.getItem('token') ?? '{}');
    const response = await fetch('/api/user/dashboard', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'authorization': `Bearer ${userData.token}`
        },
        credentials: 'include'
    });

    if (!response.ok) {
        return alert('Network response was not ok');
    }

    const data = await response.json();

    return data;
};

onMounted(async () => {
    document.addEventListener('click', handleClickOutside);
    const data = await getDashboardData();
    dashboardData.value = {
        totalRegisteredUser: data.data.total_user,
        totalAvtiveUser: data.data.total_active_user,
        totalActiveChatRoom: data.data.active_chat_rooms
    };
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

</script>

<style scoped>
    .statistics {
        display: flex;
        gap: 2rem;
        margin-top: 20px;
    }
    .statistics .statistic-item {
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
    .statistic-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        width: 60px;
        border-top-left-radius: 5px;
        border-bottom-left-radius: 5px;
    }
    .statistic-icon.total-users {
        background-color: #007bff;
    }
    .statistic-icon.active-users {
        background-color: #dc3545;
    }
    .statistic-icon.active-chatroom {
        background-color: #28a745;
    }
    .statistic-text {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    .statistic-text p {
        font-size: 14px;
    }
</style>