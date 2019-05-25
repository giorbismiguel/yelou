<template>
    <nav class="navbar navbar-expand-lg navbar-light ">
        <div class="container d-flex flex-column flex-md-row justify-content-between">
            <a class="navbar-brand" href="#">
                <img src="http://placehold.it/150x50?text=Logo" alt="">
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">
                <ul class="navbar-nav font-weight-bold">
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

                <div class="app_font-family navbar-right">
                    <ul class="navbar-nav d-flex justify-content-between">
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
                                         :to="{ name: 'register' }">Registrarse
                            </router-link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        name: "Navbar"
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