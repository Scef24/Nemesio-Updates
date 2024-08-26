import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/home.vue';
import Login from '../components/login.vue';
import Admin from '../components/admin.vue';

const routes = [
    { path: '/', name:'Home',component: Home },
    { path: '/login',name: 'Login', component: Login },
    { path: '/admin', name: 'AdminDashboard', component: Admin, meta: { requiresAuth: true } },
    { path: '/:catchAll(.*)', redirect: '/login' },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');

    if (to.meta.requiresAuth && !isAuthenticated) {
        next({ name: 'Login' });
    } else {
        next();
    }
});

export default router;