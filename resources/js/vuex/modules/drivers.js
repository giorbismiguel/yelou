import axios from 'axios'

const state = {
    markers: [],
}

const actions = {
    getDriversAvailable({commit, dispatch}) {
        return new Promise((resolve, reject) => {
            axios.get(route('api.drivers.available'))
                .then(({data: {data}}) => {
                    commit('GET_DRIVERS_AVAILABLE_OK', data)
                    resolve()
                })
                .catch(error => {
                    reject(error.response.data)
                })
        })
    }
}

const mutations = {
    GET_DRIVERS_AVAILABLE_OK(state, markers) {
        state.markers = markers
    }
};

export default {
    state,
    actions,
    mutations
}