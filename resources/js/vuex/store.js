import Vue from "vue";
import Vuex from "vuex";
import {router} from "../router";
import Company from "./modules/company";

import {app} from "../app";

import axios from "axios";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
       test : 1
    },
    mutations : {
        setTest(state, data){
            state.test = data;
        }
    },
    actions : {

    },
    getters: {
        getTest(state){
            return state.test;
        }
    },
    modules: {
        Company
    }
});

export default store;
