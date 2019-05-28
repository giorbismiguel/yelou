import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/pages/Home'
import Register from './components/pages/auth/Register'
import RegisterAs from './components/layout/RegisterAs'
import Login from './components/pages/auth/Login'
import VerifyPhone from './components/pages/auth/VerifyPhone'
import NewCode from './components/pages/auth/NewCode'

import NotFound from './components/NotFound';

const routes = [
    {
        path: '/', name: 'home', component: Home
    },
    {
        path: '/entrar', name: 'login', component: Login
    },
    {
        path: '/registrarse', name: 'register_as', component: RegisterAs
    },
    {
        path: '/registrarse/:type', name: 'register', component: Register
    },
    {
        path: '/verificar/codigo', name: 'verify_phone', component: VerifyPhone
    },
    {
        path: '/codigo/nuevo', name: 'new_code', component: NewCode
    },
    {
        path: '*', component: NotFound
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router
