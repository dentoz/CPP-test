require('./bootstrap');

import { createApp } from "vue";
import router from "./routers";

import "../css/app.css";
import App from "./App.vue";

import "vuetify/styles";
import { createVuetify } from "vuetify";
import { aliases, mdi } from "vuetify/iconsets/mdi";
import * as components from "vuetify/components";
import * as directives from "vuetify/directives";
import "@mdi/font/css/materialdesignicons.css";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

if ("serviceWorker" in navigator) {
    navigator.serviceWorker
        .register("/firebase-messaging-sw.js")
        .then((registration) => {
            console.log("Service Worker registered:", registration);
        })
        .catch((err) => {
            console.error("Service Worker registration failed:", err);
        });
}

const vuetify = createVuetify({
    components,
    directives,
    icons: {
        defaultSet: "mdi",
        aliases,
        sets: {
            mdi,
        },
    },
});

const el = document.getElementById("app");

const prefetchedData = JSON.parse(el.dataset.prefetch);

const app = createApp(App, { prefetchedData });

app.use(router).use(vuetify).use(Toast);

app.mount("#app");
