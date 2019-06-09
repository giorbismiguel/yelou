require('./bootstrap');

import Vue from 'vue';
import es from 'vee-validate/dist/locale/es';
import VeeValidate, {Validator} from 'vee-validate'
import Notifications from 'vue-notification'
import * as VueGoogleMaps from 'vue2-google-maps'
import vSelect from 'vue-select'

Vue.use(VueGoogleMaps, {
    load: {
        key: 'AIzaSyBfGvG2NXZMhrMULA6pwDWYDA819GySyXs',
        libraries: 'places',
    }
})

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
    // store.dispatch('addToastMessage', {
    //     text: error.response.data.message || 'Request error status: ' + error.response.status,
    //     type: 'danger'
    // })

    return Promise.reject(error)
})

// Global Vue Components
Vue.component('navbar', require('./components/layout/Navbar.vue').default)
Vue.component('feet', require('./components/layout/Feet.vue').default)
Vue.component('sidebar', require('./components/layout/Sidebar').default)
Vue.component('ye-actions', require('./components/modules/Actions').default);
Vue.component('ye-table', require('./components/modules/simple-table').default);
Vue.component('ye-select', vSelect)

import App from './components/App'

// Vue plugins
Vue.use(VeeValidate);
Validator.localize('es', es);
Vue.use(Notifications)

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
        router.replace('/')
    })
    .catch(() => {
    })