import axios from 'axios'
import { makeMutations } from '../helpers'

const state = {
  loading: false,
  dashboard: {},
  admin_dashboard: {
    fastest_run: {user: {}},
    longest_run: {user: {}},
  },
}

const actions = {

  stopLoading ({commit}) {
    commit('STOP_LOADING')
  },

  loadDashboard ({commit, dispatch}) {
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
  }
}

const mutations = {

  ...makeMutations([
    'CHECK_LOGIN',
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
    state.loading = false
  }),

  LOAD_DASHBOARD_OK (state, dashboard) {
    state.dashboard = dashboard
    state.loading = false
  }

}

export default {
  state,
  actions,
  mutations
}
