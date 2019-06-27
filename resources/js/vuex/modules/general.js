import axios from 'axios'
import {makeMutations} from '../helpers'

const state = {
    generalLoading: false,
    dashboard: {},
    admin_dashboard: {
        fastest_run: {user: {}},
        longest_run: {user: {}},
    },
    rate: null
}

const actions = {

    stopLoading({commit}) {
        commit('STOP_LOADING')
    },

    loadDashboard({commit, dispatch}) {
        commit('LOAD_DASHBOARD')

        return new Promise((resolve, reject) => {
            axios.get(Config.apiPath + 'dashboard/data')
                .then(response => {
                    commit('LOAD_DASHBOARD_OK', response.data)
                    resolve()
                })
                .catch(error => {
                    commit('LOAD_DASHBOARD_FAIL')
                    reject(error.response.data)
                })
        })
    },

    calculateRate({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.distance.calculate_rate'), form)
                .then(response => {
                    commit('DISTANCE_CALCULATE_OK', response.data)
                    resolve()
                })
                .catch(error => {
                    commit('DISTANCE_CALCULATE_FAIL')
                    reject(error.response.data)
                })
        })
    },

    loadAdminDashboard({commit, dispatch}) {
        commit('LOAD_ADMIN_DASHBOARD')

        return new Promise((resolve, reject) => {
            axios.get(Config.apiPath + 'dashboard/admin-data')
                .then(response => {
                    commit('LOAD_ADMIN_DASHBOARD_OK', response.data)
                    resolve()
                })
                .catch(error => {
                    commit('LOAD_ADMIN_DASHBOARD_FAIL')
                    reject(error.response.data)
                })
        })
    },

}

const mutations = {
    ...makeMutations([
        'CHECK_LOGIN',
    ], (state) => {
        state.generalLoading = true
    }),

    ...makeMutations([
        'LOGIN',
        'REGISTER'
    ], (state) => {
        state.loading = true
    }),

    ...makeMutations([
        'STOP_LOADING',
        'CHECK_LOGIN_OK',
        'CHECK_LOGIN_FAIL',
        'LOGIN_OK',
        'LOGIN_FAIL',
        'REGISTER_OK',
        'REGISTER_FAIL'
    ], (state) => {
        state.generalLoading = false
    }),

    LOAD_DASHBOARD_OK(state, dashboard) {
        state.dashboard = dashboard
        state.loading = false
    },

    LOAD_ADMIN_DASHBOARD_OK(state, dashboard) {
        state.admin_dashboard = dashboard
        state.loading = false
    },

    DISTANCE_CALCULATE_OK(state, data) {
        state.rate = data
    },

    DISTANCE_CALCULATE_FAIL(state) {
        state.rate = null
    }
}

export default {
    state,
    actions,
    mutations
}
