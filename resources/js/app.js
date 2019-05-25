require('./bootstrap');
window.Vue = require('vue');

import router from './router' // vue-router instance

// Global Vue Components
Vue.component('navbar', require('./components/layout/Navbar.vue').default)
Vue.component('feet', require('./components/layout/Feet.vue').default)

import App from './components/App'
/**
 * Application
 *
 * @type {Vue$2}
 */
/* eslint-disable no-new */
new Vue({
    el: '#app',
    components: {App},
    router
});
