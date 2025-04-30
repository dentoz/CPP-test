<template>
    <v-app>
        <div class="dashboard" ref="showSidebar">
            <Header @sidebarHandler="toggleSidebar"></Header>
            <div class="main-content">
                <aside class="sidebar">
                    <ul>
                        <li><a href="#">Menu Item 1</a></li>
                        <li><a href="#">Menu Item 2</a></li>
                        <li><a href="#">Menu Item 3</a></li>
                    </ul>
                </aside>
                <section class="content">
                    <p>Welcome to the dashboard!</p>
                </section>
            </div>
            <Sidebar :showSidebar="showSidebar"></Sidebar>
        </div>
    </v-app>
</template>

<script setup>
import { inject, ref, onMounted, onUnmounted } from 'vue'
import Header from '../components/HeaderComponent.vue';
import Sidebar from '../components/sidebarComponent.vue';

const prefetchedData = inject('prefetchedData', null);
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

onMounted(() => {
  document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
  document.removeEventListener('click', handleClickOutside);
});

</script>

<style scoped>
.v-application {
    background-color: transparent;
}
</style>