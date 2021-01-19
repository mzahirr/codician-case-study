import axios from "axios";
import {app} from "../../app";

const state = {
    companies: [],
    company:[]
};

const mutations = {
    setCompanies(state, companies) {
        state.companies = companies;
    },
    setCompany(state, company){
        state.company = company;
    }
};

const actions = {
    async setCompanies(context) {
        let st = this;
        let response = await axios.get("/api/companies").then(response => {
            if(response.status === 200) {
                context.commit("setCompanies", response.data.data.companies);
            }
        });
    },

    async setCompany(context, companyId) {
        let st = this;
        let response = await axios.get("/api/companies/" + companyId).then(response => {
            if(response.status === 200) {
                context.commit("setCompany", response.data.data.company);
            }
        });
    }
};

const getters = {
    getCompanies(state) {
        return state.companies;
    },
    getCompany(state) {
        return state.company;
    }
};

export default {
    state: state,
    mutations: mutations,
    actions: actions,
    getters: getters
}
