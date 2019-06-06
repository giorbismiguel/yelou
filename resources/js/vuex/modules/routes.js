import axios from 'axios'

const state = {
    route: null,
}

const actions = {
    createRoute({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.routes.store'), form)
                .then(({data}) => {
                    commit('CREATE_ROUTE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('CREATE_ROUTE_FAIL')
                    reject(error.response.data)
                })
        })
    },

    updateRoute({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.put(route('api.routes.update', form.id), form)
                .then(({data}) => {
                    commit('EDIT_ROUTE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('EDIT_ROUTE_FAIL')
                    reject(error.response.data)
                })
        })
    },

    getRoute({commit, dispatch}, id) {
        return new Promise((resolve, reject) => {
            axios.get(route('api.routes.show', id))
                .then(({data: {data}}) => {
                    commit('GET_ROUTE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('GET_ROUTE_FAIL')
                    reject(error.response.data)
                })
        })
    },

    deleteRoute({commit, dispatch}, id) {
        return new Promise((resolve, reject) => {
            axios.delete(route('api.routes.destroy', id))
                .then((data) => {
                    commit('DELETE_ROUTE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('DELETE_ROUTE_FAIL')
                    reject(error.response.data)
                })
        })
    }
}

const mutations = {
    CREATE_ROUTE_OK(state, route) {
        state.route = route.data
    },

    CREATE_ROUTE_FAIL(state, route) {
        state.route = route
    },

    GET_ROUTE_OK(state, route) {
        state.route = route
    },

    GET_ROUTE_FAIL(state) {
        state.route = null
    },

    EDIT_ROUTE_OK(state, route) {
        state.route = route
    },

    EDIT_ROUTE_FAIL(state, route) {
        state.route = route
    },

    DELETE_ROUTE_OK(state, route) {
        state.route = route
    },

    DELETE_ROUTE_FAIL(state, route) {
        state.route = route
    },
}

export default {
    state,
    actions,
    mutations
}