import './bootstrap';

// THIS FOR ADMINLTE3 FILES
import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
// import 'admin-lte/dist/js/demo.js';


// for preloader
import loader from 'vue3-ui-preloader';

// FOR laravel-permission-to-vuejs
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs';
import DatePicker from '@alireza-ab/vue3-persian-datepicker';



// FOR ADDING SIDEBAR AND NAVBAR TO BLADE
import Sidebar from './pages/layouts/Sidebar.vue';
import Navbar from './pages/layouts/Navbar.vue';
import Footer from './pages/layouts/Footer.vue';
// FOR LOGIN PAGE
import Login from './pages/auth/Login.vue';


// FOR GENERAL SECTION
import { createApp } from 'vue';
// import { createApp } from 'vue/dist/vue.esm-bundler.js'; // IF NOT WORK UNCOMMENT THIS
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';

import i18n from './lang/z_locale.js';

const app = createApp({});
// FOR MASTER PAGE
app.component('sidebar_vue', Sidebar);
app.component('navbar_vue', Navbar);
app.component('footer_vue', Footer);
app.component('loader_vue', loader);
app.component('date-picker', DatePicker);

// for login page
app.component('Login_vue', Login);


const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});


app.use(i18n);
app.use(router);

// FOR PERMISSIONS
app.use(LaravelPermissionToVueJS);

app.mount('#app');

