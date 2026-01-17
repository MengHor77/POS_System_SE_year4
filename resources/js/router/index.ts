import { createRouter, createWebHistory } from 'vue-router';
import Home from '../pages/frontend/Home.vue';
import AdminLogin from '../pages/backend/AdminLogin.vue';
import AdminDashboard from '../pages/backend/Dashboard.vue';

const routes = [
  { path: '/', name: 'home', component: Home },
  { path: '/admin/login', name: 'admin-login', component: AdminLogin },
  { path: '/admin/dashboard', name: 'admin-dashboard', component: AdminDashboard },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
