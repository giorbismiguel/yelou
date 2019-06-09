import axios from 'axios'

const state = {
    p: [],
}

const actions = {
    createRequestService({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.request_services.store'), form)
                .then(({data}) => {
                    commit('CREATE_REQUEST_SERVICES_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('CREATE_REQUEST_SERVICES_FAIL')
                    reject(error.response.data)
                })
        })
    }
}

const mutations = {
    CREATE_REQUEST_SERVICES_OK(state, nomenclators) {
        state.lists = nomenclators
    },

    CREATE_REQUEST_SERVICES_FAIL(state, nomenclators) {
        state.lists = nomenclators
    }
};

export default {
    state,
    actions,
    mutations
}