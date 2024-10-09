import { createRouter, createWebHistory } from 'vue-router';
import Home from '../components/home.vue';
import Login from '../components/login.vue';
import Admin from '../components/admin.vue';
import SuperAdmin from '../components/SuperAdmin.vue';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/login', name: 'Login', component: Login },
    { path: '/admin', name: 'AdminDashboard', component: Admin, meta: { requiresAuth: true } },
    { path: '/:catchAll(.*)', redirect: '/login' },
    {
        path: '/superAdmin',
        name: 'SuperAdmin',
        component: SuperAdmin,
        meta: { requiresAuth: true, requiresSuperAdmin: true }
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    const userRole = localStorage.getItem('user_role');
    console.log('Navigation Guard: isAuthenticated:', isAuthenticated);
    console.log('Navigation Guard: userRole:', userRole);
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!isAuthenticated) {
            next({ name: 'Login' });
        } else if (to.matched.some(record => record.meta.requiresSuperAdmin)) {
            if (userRole !== 'super_admin') {
                console.log('Navigation Guard: Redirecting to Home due to role mismatch');
                
                next({ name: 'Home' });
            } else {
                next();
            }
        } else {
            next();
        }
    } else {
        next();
    }
});

export default router;