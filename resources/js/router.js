import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);


import Dashboard from "./components/Dashboard";
import Company from "./components/Company";
import CompanyDetail from "./components/CompanyDetail";
import store from "./vuex/store";

export const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'company',
            component: Company,

        },
        {
            path: '/company',
            name: 'company',
            component: Company,
        },
        {
            path: '/companies/:id',
            name: 'company-detail',
            component: CompanyDetail,
            meta: {reuse: false},
        },
        ]
});
