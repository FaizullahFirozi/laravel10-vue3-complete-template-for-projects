import './bootstrap';

// THIS FOR ADMINLTE3 FILES
import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
// import 'admin-lte/dist/js/demo.js';

// for preloader
import loader from 'vue3-ui-preloader';

// FOR laravel-permission-to-vuejs
import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'



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


// this section is only for lang
import { createI18n } from 'vue-i18n';
import EN from './lang/en.json';
import PS from './lang/ps.json';
import DR from './lang/dr.json';

// for login



const i18n = createI18n({
    // something vue-i18n options here ...
    locale: 'PS',
    messages: {
        EN: EN,
        // EN: EN,
        PS: PS,
        DR: DR,
    }
});

const app = createApp({});
// FOR MASTER PAGE
app.component('sidebar_vue', Sidebar);
app.component('navbar_vue', Navbar);
app.component('footer_vue', Footer);
app.component('loader_vue', loader);

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

