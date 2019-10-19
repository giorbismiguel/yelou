<template>
    <nav class="navbar navbar-expand-md navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
                <router-link :to="{ name: 'home' }"><img src="/img/logo.png" alt="Logo"></router-link>
            </a>

            <button class="navbar-toggler navbar-toggler-left" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <div v-if="!me" class="navbar-nav w-100 justify-content-center font-weight-bold">
                    <a class="nav-link" href="#">Productos</a>

                    <a class="nav-link" href="#">Empresa</a>

                    <a class="nav-link" href="#">Seguridad</a>

                    <a class="nav-link" href="#">Ayuda</a>
                </div>

                <div v-if="me" class="navbar-nav w-100 justify-content-end app_font-family">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button"
                           aria-haspopup="true" aria-expanded="false">
                            <img class="img-avatar" width="80" height="50" src="/img/person-icon-1680.png" alt="Avatar">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <router-link :to="{ name: 'administration' }" class="dropdown-item">
                                <i class="fas fa-chart-bar"></i> Administración
                            </router-link>

                            <router-link :to="{ name: 'account' }" class="dropdown-item">
                                <i class="fas fa-user"></i> Su perfil {{ me.name }}
                            </router-link>

                            <router-link :to="{ name: 'reset_password' }" class="dropdown-item">
                                <i class="fas fa-key"></i> Cambiar contraseña
                            </router-link>

                            <a class="dropdown-item" href="#" @click.prevent="closeSession">
                                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                            </a>
                        </div>
                    </div>
                </div>

                <div v-if="!me" class="navbar-nav w-100 justify-content-end app_font-family">
                        <a class="nav-link" href="#"><i class="fas fa-globe"></i> Es</a>
                        <router-link class="nav-link" :to="{ name: 'login' }" tag="a">
                            <i class="far fa-user-circle"></i>
                            Iniciar sesión
                        </router-link>
                        <router-link class="nav-link btn my-2 my-sm-0 app_btn-primary app_font-family app_color"
                                     :to="{ name: 'register_as' }" tag="a">Registrarse
                        </router-link>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mapState, mapActions} from 'vuex'

    export default {
        name: "Navbar",

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
        },

        methods: {
            ...mapActions([
                'logout',
            ]),

            closeSession() {
                this.logout(this.form)
                    .then(() => {
                        this.$router.replace('/')
                    })
                    .catch((data) => {
                    })
            }
        }
    }
</script>

<style scoped>
    nav.navbar {
        min-height: 84px;
        background: rgb(33, 61, 94);
    }

    nav div.navbar-right {
        width: 350px;
    }
</style>