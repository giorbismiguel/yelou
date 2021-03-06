import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './vuex/store'

Vue.use(VueRouter)

import Home from './components/pages/Home'

import Register from './components/pages/auth/Register'
import TermCondition from './components/pages/auth/TermCondition';
import SelectRegister from './components/pages/auth/SelectRegister'
import Login from './components/pages/auth/Login'
import RenewPassword from './components/pages/auth/RenewPassword'
import ResetPasswordCreate from './components/pages/auth/ResetPasswordCreate'
import VerifyPhone from './components/pages/auth/VerifyPhone'
import NewCode from './components/pages/auth/NewCode'

import ResetPassword from './components/pages/auth/ResetPassword'
import Profile from './components/pages/profile/Profile'
import Administration from './components/pages/administration/Index'

import Services from './components/pages/administration/services/Index'
import CreateService from './components/pages/administration/services/Create'
import AcceptService from './components/pages/administration/services/Accept'

import Routes from './components/pages/administration/routes/Index'
import CreateRoute from './components/pages/administration/routes/Create'
import EditRoute from './components/pages/administration/routes/Edit'

import Tours from './components/pages/administration/tours/Index'

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
        path: '/actualizar/clave/:token', name: 'actualizar_clave', component: RenewPassword
    },
    {
        path: '/registrarse', name: 'register_as', component: SelectRegister
    },
    {
        path: '/registrarse/:type', name: 'register', component: Register
    },
    {
        path: '/terminos-condiciones', name: 'term_condition', component: TermCondition
    },
    {
        path: '/verificar/codigo', name: 'verify_phone', component: VerifyPhone
    },
    {
        path: '/codigo/nuevo', name: 'new_code', component: NewCode
    },
    {
        path: '/cuenta', name: 'account', component: Profile, meta: {requiresAuth: true}
    },
    {
        path: '/cuenta/clave', name: 'reset_password', component: ResetPassword, meta: {requiresAuth: true}
    },
    {
        path: '/administracion', name: 'administration', component: Administration, meta: {requiresAuth: true}
    },
    {
        path: '/servicios', name: 'services', component: Services, meta: {requiresAuth: true}
    },
    {
        path: '/servicios/adicionar', name: 'services_create', component: CreateService, meta: {requiresAuth: true}
    },
    {
        path: '/servicios/aceptar/:service/:driver', component: AcceptService
    },
    {
        path: '/rutas', name: 'routes', component: Routes, meta: {requiresAuth: true}
    },
    {
        path: '/rutas/adicionar', name: 'routes_create', component: CreateRoute, meta: {requiresAuth: true}
    },
    {
        path: '/rutas/modificar/:id', name: 'routes_edit', component: EditRoute, meta: {requiresAuth: true}
    },
    {
        path: '/recorridos', name: 'tours', component: Tours, meta: {requiresAuth: true}
    },
    {
        path: '*', component: NotFound
    }
]

const router = new VueRouter({
    routes,
    mode: 'history',
    history: false,
})

/**
 * Authenticated routes
 */
router.beforeEach((to, from, next) => {
    if (to.matched.some(record => record.meta.requiresAuth) && !store.state.auth.me) {
        // if route requires auth and user isn't authenticated
        next('/entrar')
    } else {
        next()
    }
})

export default router
