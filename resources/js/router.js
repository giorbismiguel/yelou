import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/pages/Home'
import Register from './components/pages/auth/Register'
import RegisterAs from './components/layout/RegisterAs'
import Login from './components/pages/auth/Login'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/entrar',
        name: 'login',
        component: Login
    },
    {
        path: '/registrarse',
        name: 'register_as',
        component: RegisterAs
    },
    {
        path: '/registrarse/:type',
        name: 'register',
        component: Register
    }
]

const router = new VueRouter({
    mode: 'history',
    routes
})

export default router
