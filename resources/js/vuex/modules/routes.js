import axios from 'axios'

const state = {
    route: null,
}

const actions = {
    create({commit, dispatch}, form) {
        commit('CREATE_ROUTE')

        return new Promise((resolve, reject) => {
            axios.post(route(''), form)
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
    CREATE_ROUTE(state, user) {
        state.me = user
    },

    CREATE_ROUTE_OK(state, user) {
        state.me = user
    },

    CREATE_ROUTE_FAIL(state, user) {
        state.me = user
    }
}

export default {
    state,
    actions,
    mutations
}