import './bootstrap';

import 'admin-lte/plugins/jquery/jquery.min.js';
import 'admin-lte/plugins/bootstrap/js/bootstrap.bundle.min.js';
import 'admin-lte/dist/js/adminlte.min.js';
import 'admin-lte/dist/js/demo.js';

import { createApp } from 'vue/dist/vue.esm-bundler.js';
import { createRouter, createWebHistory } from 'vue-router';
import Routes from './routes.js';

// this section is only for lang
import { createI18n } from 'vue-i18n';
import EN from './lang/en.json';
import PS from './lang/ps.json';
import DR from './lang/dr.json';
const i18n = createI18n({
    // something vue-i18n options here ...
    locale: 'PS',
    messages: {
        EN: EN,
        PS: PS,
        DR: DR,
    }
});

const app = createApp({});

const router = createRouter({
    routes: Routes,
    history: createWebHistory(),
});


app.use(i18n);
app.use(router);

app.mount('#app');

