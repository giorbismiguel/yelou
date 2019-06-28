import axios from 'axios'

const state = {
    markers: [],
    update_driver_available: null,
}

const actions = {
    getDriversAvailable({commit, dispatch}, location) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.drivers.available'), location)
                .then(({data: {data}}) => {
                    commit('GET_DRIVERS_AVAILABLE_OK', data)
                    resolve()
                })
                .catch(error => {
                    reject(error.response.data)
                })
        })
    },

    acceptDriverService({commit, dispatch}, id) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.requested_services.accept.client', id))
                .then(success => {
                    resolve(success)
                })
                .catch(error => {
                    reject(error.response.data)
                })
        })
    },

    activeDriverService({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.transportation_availables.store', form))
                .then(response => {
                    commit('UPDATE_DRIVERS_AVAILABLE_OK', response)
                    resolve(success)
                })
                .catch(error => {
                    commit('UPDATE_DRIVERS_AVAILABLE_FAIL')
                    reject(error)
                })
        })
    }
}

const mutations = {
    GET_DRIVERS_AVAILABLE_OK(state, markers) {
        state.markers = markers
    },

    UPDATE_DRIVERS_AVAILABLE_OK(data) {
        state.update_driver_available = data
    },

    UPDATE_DRIVERS_AVAILABLE_FAIL() {
        state.update_driver_available = null
    }
};

export default {
    state,
    actions,
    mutations
}