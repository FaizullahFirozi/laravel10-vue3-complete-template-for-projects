// import Dashboard from './components/Dashboard.vue'; //this is bad way when load page all will be loaded.
// import Dashboard2 from './components/Dashboard2.vue';

export default [
    {
        path: '/admin/dashboard',
        name: '/admin/dashboard',
        component: () => import('./components/Dashboard.vue'), //good way for dynamic load
    },
    {
        path: '/admin/dashboard2',
        name: '/admin/dashboard2',
        component: () => import('./components/Dashboard2.vue'),
    },
];