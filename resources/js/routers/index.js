import { createRouter, createWebHistory } from 'vue-router';

import Login from '../pages/Login.vue';
import Dashboard from '../pages/Dashboard.vue';
import ChatRoom from '../pages/ChatRoom.vue';
import NewChatRoom from '../pages/NewChatRoom.vue';

const routes = [
  { path: '/', component: Login },
  { path: '/dashboard', component: Dashboard },
  { path: '/chatroom', component: ChatRoom },
  { path: '/chatroom/new-chatroom', component: NewChatRoom }
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
