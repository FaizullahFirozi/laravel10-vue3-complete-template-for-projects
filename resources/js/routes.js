// import Dashboard from './Dashboard.vue'; //this is bad way when load page all will be loaded.
// import Dashboard2 from './Dashboard2.vue';

 


export default [
    {
        path: '/', //THIS IS FOR TEST
        redirect: '/admin/dashboard',
    },
    {
        path: '/:pashMatch(.*)',
        name: 'notfound',
        component: () => import('./pages/notFoundPage/NotFoundPage.vue'),
        // redirect: '/admin/dashboard',   //BUT IT HAS PROBLEM IF TYPE INCURRECT YOU RETURN TO HERER.
    },
    {
        path: '/login',
        name: 'admin.login',
        component: () => import('./pages/auth/Login.vue'), //good way for dynamic load
    },
    {
        path: '/admin/users',
        name: 'admin.users',
        component: () => import('./pages/users/UsersList.vue'), //good way for dynamic load
    },
    {
        path: '/admin/profile',
        name: 'admin.profile',
        component: () => import('./pages/profile/updateProfile.vue'), //good way for dynamic load
    },
    {
        path: '/admin/dashboard',
        name: 'admin.dashboard',
        component: () => import('./pages/Dashboard.vue'), //good way for dynamic load
    },
    {
        path: '/admin/dashboard2',
        name: 'admin.dashboard2',
        component: () => import('./pages/Dashboard2.vue'),
    },
    {
        path: '/admin/CRUD-Test',
        name: 'admin.crudTest',
        component: () => import('./pages/crudTest/crudTest.vue'),
    },
    {
        path: '/admin/roles',
        name: 'admin.roles',
        component: () => import('./pages/roles/RolesList.vue'),
    },
    {
        path: '/admin/roles/add',
        name: 'admin.roles.add',
        component: () => import('./pages/roles/RolesAdd.vue'),
    },
    {
        path: '/admin/roles/:id/edit',
        name: 'admin.roles.edit',
        component: () => import('./pages/roles/RolesEdit.vue'),
    },
    {
        path: '/admin/activity-log',
        name: 'admin.activity-log',
        component: () => import('./pages/activityLog/ActivityLog.vue'),
    },
];