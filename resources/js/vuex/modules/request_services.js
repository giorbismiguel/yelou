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
    },

    acceptRequestedService({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            console.log(form);

            axios.get(route('api.requested_services.accept', {service_id: form.service_id, driver_id: form.driver_id}))
                .then(({data}) => {
                    commit('CREATE_REQUESTED_SERVICES_OK', data)

                    resolve()
                })
                .catch(error => {
                    commit('CREATE_REQUESTED_SERVICES_FAIL')
                    console.log(error.response)
                    reject(error.response)
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
    },

    CREATE_REQUESTED_SERVICES_OK(state) {

    },

    CREATE_REQUESTED_SERVICES_FAIL(state) {

    }
};

export default {
    state,
    actions,
    mutations
}