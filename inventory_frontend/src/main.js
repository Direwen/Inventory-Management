import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import router from './router/index'
import { createPinia } from 'pinia'
import { createI18n } from 'vue-i18n'
import "toastify-js/src/toastify.css"
import en from "./locales/en.json"
import my from "./locales/my.json"
import th from "./locales/th.json"
import cn from "./locales/cn.json"
import es from "./locales/es.json"

const pinia = createPinia()

const i18n = createI18n({
    locale: 'en', // Default locale
    fallbackLocale: 'en', // Fallback if translation is missing
    messages: {
        en: en,
        my: my,
        th: th,
        cn: cn,
        es: es
    }
})

createApp(App)
    .use(router)
    .use(pinia)
    .use(i18n)
    .mount('#app')
