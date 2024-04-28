import Dashboard from './components/Dashboard.vue';
import Dashboard2 from './components/Dashboard2.vue';

export default [
    {
        path: '/admin/dashboard',
        name: '/admin/dashboard',
        component: Dashboard,
    },
    {
        path: '/admin/dashboard2',
        name: '/admin/dashboard2',
        component: Dashboard2,
    },
];