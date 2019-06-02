import axios from 'axios'
import {makeMutations} from '../helpers'

const state = {
    generalLoading: false,
    dashboard: {},
    admin_dashboard: {
        fastest_run: {user: {}},
        longest_run: {user: {}},
    },
}

const actions = {

    stopLoading({commit}) {
        commit('STOP_LOADING')
    },

    loadDashboard({commit, dispatch}) {
        commit('LOAD_DASHBOARD')

        return new Promise((resolve, reject) => {
            axios.get(Config.apiPath + 'dashboard/data')
                .then(
                    response => {
                        commit('LOAD_DASHBOARD_OK', response.data)
                        resolve()
                    })
                .catch(error => {
                    commit('LOAD_DASHBOARD_FAIL')
                    reject(error.response.data)
                })
        })
    },

    loadAdminDashboard({commit, dispatch}) {
        commit('LOAD_ADMIN_DASHBOARD')

        return new Promise((resolve, reject) => {
            axios.get(Config.apiPath + 'dashboard/admin-data')
                .then(
                    response => {
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
        'CHECK_LOGIN_OK',
        'CHECK_LOGIN_FAIL',
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

}

export default {
    state,
    actions,
    mutations
}
