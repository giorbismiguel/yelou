import axios from 'axios'

const state = {
    me: null, // Logged in user,
    phone_verify: null, // Phone Verify,
    active: null, // Active User,
}

const actions = {

    checkLogin({commit, dispatch}) {
        commit('CHECK_LOGIN')

        const accessToken = localStorage.getItem('access_token')

        return new Promise((resolve, reject) => {
            if (!accessToken) {
                commit('CHECK_LOGIN_FAIL')
                return reject(new Error('No access token stored'))
            }

            axios.get(Config.apiPath + 'user/me')
                .then(
                    response => {
                        commit('CHECK_LOGIN_OK', response.data)
                        resolve()
                    })
                .catch(error => {
                    localStorage.removeItem('access_token')
                    commit('CHECK_LOGIN_FAIL')
                    reject(error.response.data)
                })
        })
    },


    login({commit, dispatch}, form) {
        commit('LOGIN')

        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/login', form)
                .then(({data}) => {
                    if (data.access_token) {
                        const accessToken = data.access_token
                        localStorage.setItem('access_token', accessToken)
                    }

                    commit('LOGIN_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('LOGIN_FAIL')
                    reject(error.response.data)
                })
        })
    },

    active_account({commit, dispatch}, form) {
        commit('ACTIVE_ACCOUNT')

        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/active', form)
                .then(({data}) => {
                    commit('ACTIVE_ACCOUNT_OK', data.active)
                    resolve()
                })
                .catch(error => {
                    commit('ACTIVE_ACCOUNT_FAIL')
                    reject(error.response.data)
                })
        })
    },

    logout({commit, dispatch}) {
        commit('LOGOUT_OK')

        localStorage.removeItem('access_token')
    },

    register({commit, dispatch}, form) {
        commit('REGISTER')

        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/register', form)
                .then(
                    response => {
                        const accessToken = response.data.access_token
                        localStorage.setItem('access_token', accessToken)

                        commit('REGISTER_OK', response.data.user)
                        resolve()
                    })
                .catch(error => {
                    commit('REGISTER_FAIL')
                    reject(error.response.data)
                })
        })
    },

    updateProfile({commit, dispatch}, {id, form}) {
        commit('UPDATE_PROFILE')

        return new Promise((resolve, reject) => {
            axios.post(Config.apiPath + 'user/' + id, {_method: 'PUT', ...form})
                .then(
                    response => {
                        commit('UPDATE_PROFILE_OK', response.data.user)
                        resolve()
                    })
                .catch(error => {
                    commit('UPDATE_PROFILE_FAIL')
                    reject(error.response.data)
                })
        })
    },

}

const mutations = {

    CHECK_LOGIN_OK(state, user) {
        state.me = user
    },

    LOGIN_OK(state, data) {
        state.me = data.user
        state.phone_verify = data.phone_verify
    },

    LOGOUT_OK(state) {
        state.me = null
    },

    REGISTER_OK(state, user) {
        state.me = user
    },

    UPDATE_PROFILE_OK(state, user) {
        state.me = user
    },

    ACTIVE_ACCOUNT(state, user) {
        state.me = user
    },

    ACTIVE_ACCOUNT_OK(state, active) {
        state.active = active
    },

    ACTIVE_ACCOUNT_FAIL(state, user) {
        state.active = false
    },

}

export default {
    state,
    actions,
    mutations
}
