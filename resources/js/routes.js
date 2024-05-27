// import Dashboard from './components/Dashboard.vue'; //this is bad way when load page all will be loaded.
// import Dashboard2 from './components/Dashboard2.vue';

 


export default [
    {
        path: '/', //THIS IS FOR TEST
        redirect: '/admin/dashboard',
    },
    {
        path: '/:pashMatch(.*)',
        name: 'notfound',
        component: () => import('./components/pages/notFoundPage/NotFoundPage.vue'),
        // redirect: '/admin/dashboard',   //BUT IT HAS PROBLEM IF TYPE INCURRECT YOU RETURN TO HERER.
    },
    {
        path: '/login',
        name: 'admin.login',
        component: () => import('./components/pages/auth/Login.vue'), //good way for dynamic load
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: () => import('./components/pages/users/UsersList.vue'), //good way for dynamic load
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: () => import('./components/pages/profile/updateProfile.vue'), //good way for dynamic load
    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: () => import('./components/pages/Dashboard.vue'), //good way for dynamic load
    },
    {
        path: '/admin/dashboard2',
        name: 'admin.dashboard2',
        component: () => import('./components/pages/Dashboard2.vue'),
    },
    {
        path: '/admin/CRUD-Test',
        name: 'admin.crudTest',
        component: () => import('./components/pages/crudTest/crudTest.vue'),
    },
    {
        path: '/admin/activity-log',
        name: 'admin.activity-log',
        component: () => import('./components/pages/activityLog/ActivityLog.vue'),
    },
];