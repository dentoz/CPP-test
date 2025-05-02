<template>
    <div>
        <v-app-bar :elevation="2" style="overflow: visible;">
            <template v-slot:prepend>
                <v-app-bar-nav-icon @click="toggleSidebar"></v-app-bar-nav-icon>
            </template>

            <v-btn icon @click="toggleProfileMenu">
                <v-avatar size="40">
                    <img :src="userData.avatar" alt="User Avatar" />
                </v-avatar>
            </v-btn>

        </v-app-bar>
        <v-sheet class="profile-menu" :height="40" :width="200" :elevation="8" v-if="showIconButtonMenu">
            <button @click="handleLogout">
                <div class="profile-menu-button">
                    <v-icon size="16" style="color: #f8f9fa;">mdi-logout</v-icon>
                    Logout
                </div>
            </button>
        </v-sheet>
    </div>
</template>

<script setup>
import { defineEmits, ref } from 'vue';
import { useRouter } from 'vue-router';

const userData = JSON.parse(localStorage.getItem('token') ?? '{}');
const emit = defineEmits(['sidebarHandler']);

const router = useRouter();
const showIconButtonMenu = ref(false);
const showSidebar = ref(false);

const toggleProfileMenu = () => {
    showIconButtonMenu.value = !showIconButtonMenu.value;
};

const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value;
    emit('sidebarHandler', showSidebar.value);
};

const handleLogout = async () => {
    const response = await fetch(`/api/logout`, {
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
        return alert(responseData.message);
    }

    return router.push('/');
}
</script>

<style scoped>
.v-app-bar.v-toolbar {
    background-color: #343a40;
    color: #f8f9fa;
}

.v-avatar img {
    max-width: 40px;
}

.profile-menu {
    position: absolute;
    top: 65px;
    right: 0;
    background-color: #343a40;
    padding: 0.5rem;
}

.profile-menu-button {
    display: flex;
    align-items: center;
    justify-content: center;
    color: #f8f9fa;
    cursor: pointer;
    gap: 10px;
    width: calc(200px - 16px);
}
</style>