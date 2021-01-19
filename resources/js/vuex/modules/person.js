import axios from "axios";
import {app} from "../../app";

const state = {
    persons: [],
};

const mutations = {
    setPersons(state, persons) {
        state.persons = persons;
    },
};

const actions = {
    async setPersons(context, companyId) {
        let st = this;
        let response = await axios.get('/api/companies/' + companyId + '/persons').then(response => {
            if(response.status === 200) {
                context.commit("setCompanies", response.data.data.companies);
            }
        });
    }
};

const getters = {
    getPersons(state) {
        return state.persons;
    }
};

export default {
    state: state,
    mutations: mutations,
    actions: actions,
    getters: getters
}
