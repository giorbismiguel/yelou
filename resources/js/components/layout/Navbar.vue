<template>
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container">
            <a class="navbar-brand" href="#">
                <router-link class="nav-link" :to="{ name: 'home' }">
                    <img src="/img/logo.png" alt="Logo">
                </router-link>
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul v-if="!me" class="navbar-nav w-100 justify-content-center font-weight-bold">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Productos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Empresa</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Seguridad</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Ayuda</a>
                    </li>
                </ul>

                <ul v-if="me" class="nav navbar-nav ml-auto w-100 justify-content-end app_font-family">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img class="img-avatar" width="80" height="50" src="/img/person-icon-1680.png" alt="Avatar">
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow">
                            <a class="dropdown-item">
                                {{ me.name }}<br>
                                <small class="text-muted">email</small>
                            </a>
                            <a class="dropdown-item" href="/profile">
                                <i class="fas fa-user"></i> Perfil
                            </a>
                            <div class="divider"></div>
                            <a class="dropdown-item" href="/password">
                                <i class="fas fa-key"></i> Cambiar contraseña
                            </a>
                            <div class="divider"></div>
                            <a class="dropdown-item" href="">
                                <i class="fas fa-sign-out-alt"></i> Cerrar sesión
                            </a>
                        </div>
                    </li>
                </ul>

                <ul v-if="!me" class="nav navbar-nav ml-auto w-100 justify-content-end app_font-family">
                    <li class="nav-item">
                        <a class="nav-link" href="#"><i class="fas fa-globe"></i> Es</a>
                    </li>
                    <li class="nav-item">
                        <router-link class="nav-link" :to="{ name: 'login' }"><i class="far fa-user-circle"></i>
                            Iniciar sesión
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link class="btn my-2 my-sm-0 app_btn-primary app_font-family app_color"
                                     :to="{ name: 'register_as' }">Registrarse
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "Navbar",

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
        },
    }
</script>

<style scoped>
    nav.navbar {
        height: 84px;
        background: rgb(33, 61, 94);
    }

    nav div.navbar-right {
        width: 350px;
    }
</style>