import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import Home from './components/pages/Home'

import Register from './components/pages/auth/Register'
import RegisterAs from './components/pages/auth/RegisterAs'
import Login from './components/pages/auth/Login'
import ResetPassword from './components/pages/auth/RenewPassword'
import ResetPasswordCreate from './components/pages/auth/ResetPasswordCreate'
import VerifyPhone from './components/pages/auth/VerifyPhone'
import NewCode from './components/pages/auth/NewCode'
import Profile from './components/pages/profile/Profile'
import NotFound from './components/NotFound';

const routes = [
    {
        path: '/', name: 'home', component: Home
    },
    {
        path: '/entrar', name: 'login', component: Login
    },
    {
        path: '/restabler/clave', name: 'restabler_clave', component: ResetPasswordCreate
    },
    {
        path: '/actualizar/clave/:token', name: 'actualizar_clave', component: ResetPassword
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
        path: '/profile', name: 'profile', component: Profile
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
