require('./bootstrap');
import Vue from 'vue';
Vue.component('dashboard', require('./components/Dashboard').default);

import Vuetify from "vuetify";
import 'vuetify/dist/vuetify.min.css';
import {router} from "./router";
import store from "./vuex/store";
import VueMask from 'v-mask'
import Notifications from 'vue-notification'
import axios from "axios";



Vue.use(Vuetify);
Vue.use(VueMask);
Vue.use(Notifications);


Vue.mixin({
    methods:{
        pushNotify(payload){
            return Vue.notify({
                group: 'foo',
                type: payload.type,
                title: payload.title,
                text: payload.message,
                duration: payload.hasOwnProperty('duration') ? payload.duration : 3000
            });
        },
    }
});

export const app = new Vue({
    el : '#app',
    vuetify: new Vuetify({
        theme: {
            dark: true,
            themes: {
                dark: {
                    primary: '#3f51b5',
                    info: '#4c86b5',
                    success: '#17b535',
                    secondary: '#b0bec5',
                    accent: '#8c9eff',
                    error: '#b71c1c',
                }
            },
        }
    }),
    router : router,
    store : store
})

store.$app = app;
