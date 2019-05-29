import Vue from 'vue'
import Vuex from 'vuex'
import createLogger from 'vuex/dist/logger'
import auth from './modules/auth'
import nomenclators from './modules/nomenclators'
import general from './modules/general'

Vue.use(Vuex)

const debug = process.env.NODE_ENV !== 'production'

console.log(process.env.NODE_ENV);

export default new Vuex.Store({
    plugins: debug ? [createLogger()] : [],
    modules: {
        auth,
        nomenclators,
        general
    }
})