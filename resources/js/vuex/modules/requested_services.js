import axios from 'axios'

const state = {
    responseDeleteRequestedService: null
}

const actions = {
    deleteRequestedService({commit, dispatch}, id) {
        return new Promise((resolve, reject) => {
            axios.delete(route('api.requested_services.destroy', id))
                .then((data) => {
                    commit('DELETE_REQUESTED_SERVICES_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('DELETE_REQUESTED_SERVICES_FAIL', error)
                    reject(error.response.data)
                })
        })
    },

    beginTravel({commit, dispatch}, id) {
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
    },

    endTravel({commit, dispatch}, id) {
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
    DELETE_REQUESTED_SERVICES_OK(state, data) {
        state.responseDeleteRequestedService = data;
    },

    DELETE_REQUESTED_SERVICES_FAIL(state, error) {
        state.responseDeleteRequestedService = error
    }
};

export default {
    state,
    actions,
    mutations
}