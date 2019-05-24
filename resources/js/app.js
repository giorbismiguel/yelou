require('./bootstrap');
window.Vue = require('vue');

import router from './router' // vue-router instance

import App from './components/Home'

new Vue({
    el: '#app',
    components: {App},
    router
});
