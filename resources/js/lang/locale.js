
// this section is only for lang
import { createI18n } from 'vue-i18n';
import EN from './en.json';
import PS from './ps.json';
import DR from './dr.json';

// for login

const i18n = createI18n({
    // something vue-i18n options here ...
    // locale: document.cookie.split( '=' )[1],
    locale: 'DR',
    messages: {
        EN: EN,
        PS: PS,
        DR: DR,
    }
});

export default i18n;