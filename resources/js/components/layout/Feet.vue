<template>
    <footer class="d-flex align-items-center">
        <div class="container d-flex flex-column">
            <div class="mb-5">
                <router-link class="nav-link" :to="{ name: 'home' }">
                    <img src="/img/logo.png" alt="Logo">
                </router-link>
            </div>

            <div class="d-flex justify-content-between mt-5 text-center" style="padding: 0 40px;">
                <div class="app_font-family_sm app_color">© 2019 Yelou</div>

                <div class="app_font-family_sm other_pages">
                    <a href="">Privacidad</a>
                    <a href="">Accesibilidad</a>
                    <a href="">Condiciones</a>
                </div>

                <div class="social_networks">
                    <a href="" class="m-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="" class="m-2"><i class="fab fa-twitter"></i></a>
                    <a href="" class="m-2"><i class="fab fa-youtube"></i></a>
                    <a href="" class="m-2"><i class="fab fa-linkedin"></i></a>
                    <a href="" class="m-2"><i class="fab fa-instagram"></i></a>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
    export default {
        name: "Feet"
    }
</script>

<style scoped>
    footer {
        background: rgb(33, 61, 94);
        height: 280px;
    }

    .other_pages {
        width: 302px;
    }

    .social_networks {
        width: 248px;
    }
</style>