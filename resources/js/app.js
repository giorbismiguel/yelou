require('./bootstrap');

import Vue from 'vue';
import VeeValidate from 'vee-validate';

import store from './vuex/store' // vuex store instance
import router from './router' // vue-router instance

// Authorization header
axios.interceptors.request.use(function (config) {
    config['headers'] = {
        Authorization: 'Bearer ' + localStorage.getItem('access_token'),
        Accept: 'application/json',
    }

    return config
}, error => Promise.reject(error))

// Show toast with message for non OK responses
axios.interceptors.response.use(response => response, error => {
    store.dispatch('addToastMessage', {
        text: error.response.data.message || 'Request error status: ' + error.response.status,
        type: 'danger'
    })

    return Promise.reject(error)
})

// Global Vue Components
Vue.component('navbar', require('./components/layout/Navbar.vue').default)
Vue.component('feet', require('./components/layout/Feet.vue').default)

import App from './components/App'

// Vue plugins
Vue.use(VeeValidate);

/**
 * Application
 *
 * @type {Vue$2}
 */
/* eslint-disable no-new */
new Vue({
    el: '#app',
    components: {App},
    router,
    store
});

// Check user login status
store
    .dispatch('checkLogin')
    .then(() => {
        router.replace('/dashboard')
    })
    .catch(() => {

    })