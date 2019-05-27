import axios from 'axios'

const state = {
    lists: null,
}

const actions = {

    nomenclators({commit, dispatch}) {
        return new Promise((resolve, reject) => {
            axios
                .get('/api/v1/nomenclators')
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

}

const mutations = {
    GET_NOMENCLATORS_OK(state, nomenclators) {
        state.lists = nomenclators
    },

    GET_NOMENCLATORS_FAIL(state, nomenclators) {
        state.lists = []
    }
};

export default {
    state,
    actions,
    mutations
}