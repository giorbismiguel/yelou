import axios from 'axios'

const state = {
    requestService: null,
    responseRequested: null,
    responseDeleteService: null
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
            axios.get(route('api.requested_services.accept', {service_id: form.service_id, driver_id: form.driver_id}))
                .then((data) => {
                    commit('CREATE_REQUESTED_SERVICES_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('CREATE_REQUESTED_SERVICES_FAIL', error)
                    reject(data)
                })
        })
    },

    deleteRequestedService({commit, dispatch}, id) {
        return new Promise((resolve, reject) => {
            axios.delete(route('api.request_services.destroy', id))
                .then((data) => {
                    commit('DELETE_REQUEST_SERVICES_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('DELETE_REQUEST_SERVICES_FAIL', error)
                    reject(error.response.data)
                })
        })
    }
}

const mutations = {
    CREATE_REQUEST_SERVICES_OK(state, data) {
        state.requestService = data
    },

    CREATE_REQUEST_SERVICES_FAIL(state) {
        state.requestService = null
    },

    DELETE_REQUEST_SERVICES_OK(state, data) {
        state.responseDeleteService = data;
    },

    DELETE_REQUEST_SERVICES_FAIL(state, error) {
        state.responseDeleteService = error
    },

    CREATE_REQUESTED_SERVICES_OK(state, data) {
        state.responseRequested = data;
    },

    CREATE_REQUESTED_SERVICES_FAIL(state, data) {
        state.responseRequested = data
    }
};

export default {
    state,
    actions,
    mutations
}