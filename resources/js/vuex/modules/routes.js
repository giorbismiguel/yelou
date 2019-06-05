import axios from 'axios'

const state = {
    route: null,
}

const actions = {
    createRoute({commit, dispatch}, form) {
        commit('CREATE_ROUTE')

        return new Promise((resolve, reject) => {
            axios.post(route('api.routes.create'), form)
                .then(({data}) => {
                    commit('CREATE_ROUTE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('CREATE_ROUTE_FAIL')
                    reject(error.response.data)
                })
        })
    }
}

const mutations = {
    CREATE_ROUTE(state, route) {
        state.route = route
    },

    CREATE_ROUTE_OK(state, route) {
        state.route = route
    },

    CREATE_ROUTE_FAIL(state, route) {
        state.route = route
    }
}

export default {
    state,
    actions,
    mutations
}