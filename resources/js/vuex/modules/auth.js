import axios from 'axios'

const state = {
    me: null, // Logged in user,
    phone_verify: null, // Phone Verify,
    phone_verify_active: null, // Active User,
    code_activation: null,
    send_email: null,
    find_token_data: null,
    password_reset_data: null,
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

            axios.get(route('api.me'))
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
                    commit('ACTIVE_ACCOUNT_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('ACTIVE_ACCOUNT_FAIL')
                    reject(error.response.data)
                })
        })
    },

    new_activation_code({commit, dispatch}, form) {
        commit('ACTIVATION_CODE')

        return new Promise((resolve, reject) => {
            axios.post('/api/v1/auth/new_activation_code', form)
                .then(({data}) => {
                    commit('ACTIVATION_CODE_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('ACTIVATION_CODE_FAIL')
                    reject(error.response.data)
                })
        })
    },

    send_email({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.password.create'), form)
                .then(() => {
                    commit('SEND_EMAIL_OK')
                    resolve()
                })
                .catch(error => {
                    commit('SEND_EMAIL_FAIL')
                    reject(error.response.data)
                })
        })
    },

    password_reset({commit, dispatch}, form) {
        return new Promise((resolve, reject) => {
            axios.post(route('api.password.reset'), form)
                .then((data) => {
                    commit('PASSWORD_RESET_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('PASSWORD_RESET_FAIL')
                    reject(error.response.data)
                })
        })
    },

    find_token({commit, dispatch}, token) {
        return new Promise((resolve, reject) => {
            axios.get(route('api.password.find_token', token))
                .then(({data}) => {
                    commit('FIND_TOKEN_OK', data)
                    resolve()
                })
                .catch(error => {
                    commit('FIND_TOKEN_FAIL')
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

    updateProfile({commit, dispatch}, form) {
        //commit('UPDATE_PROFILE')

        return new Promise((resolve, reject) => {
            axios.put(route('api.users.update', form.id), form)
                .then(({data: {data}}) => {
                    commit('UPDATE_PROFILE_OK', data)
                    resolve()
                })
                .catch(error => {
                    //commit('UPDATE_PROFILE_FAIL')
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

    UPDATE_PROFILE_OK(state, data) {
        state.me = data.user
    },

    ACTIVE_ACCOUNT(state, user) {
        state.me = user
    },

    ACTIVE_ACCOUNT_OK(state, data) {
        state.phone_verify_active = data.active
    },

    ACTIVE_ACCOUNT_FAIL(state) {
        state.active = false
    },

    ACTIVATION_CODE(state, user) {
        state.me = user
    },

    ACTIVATION_CODE_OK(state, data) {
        state.code_activation = data.code_activation
    },

    ACTIVATION_CODE_FAIL(state) {
        state.active = false
    },

    SEND_EMAIL_OK(state) {
        state.send_email = true
    },

    SEND_EMAIL_FAIL(state) {
        state.send_email = false
    },

    FIND_TOKEN_OK(state, data) {
        state.find_token_data = data
    },

    FIND_TOKEN_FAIL(state) {
        state.find_token_data = false
    },

    PASSWORD_RESET_OK(state, data) {
        state.me = data
    },

    PASSWORD_RESET_FAIL(state) {
        state.password_reset_data = false
    },

}

export default {
    state,
    actions,
    mutations
}
