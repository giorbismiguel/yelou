import axios from 'axios'

const state = {
    lists: [],
    listsRequestServices: [],
}

const actions = {

    nomenclators({commit, dispatch}) {
        return new Promise((resolve, reject) => {
            axios
                .get(route('api.lists.get'))
                .then(({data: {data}}) => {
                    commit('GET_NOMENCLATORS_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('GET_NOMENCLATORS_FAIL')
                    reject(error.response.data)
                })
        })
    },

    nomenclatorsRequestServices({commit, dispatch}) {
        return new Promise((resolve, reject) => {
            axios
                .get(route('api.lists.request_services'))
                .then(({data: {data}}) => {
                    commit('GET_PAYMENT_METHOD_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('GET_PAYMENT_METHOD_FAIL')
                    reject(error.response.data)
                })
        })
    },

}

const mutations = {
    GET_NOMENCLATORS_OK(state, nomenclators) {
        state.lists = nomenclators
    },

    GET_NOMENCLATORS_FAIL(state, nomenclators) {
        state.lists = []
    },

    GET_PAYMENT_METHOD_OK(state, nomenclators) {
        state.listsRequestServices = nomenclators
    },

    GET_PAYMENT_METHOD_FAIL(state) {
        state.listsPaymentMethod = []
    }
};

export default {
    state,
    actions,
    mutations
}