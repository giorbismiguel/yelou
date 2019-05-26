<template>
    <footer>
        <div class="container d-flex flex-column">
            <div>
                Logo
            </div>
            <div class="d-flex justify-content-between">
                <div class="app_font-family_sm app_color">© 2019 Yelou</div>

                <div class="d-flex justify-content-between app_font-family_sm other_pages">
                    <a href="">Privacidad</a>
                    <a href="">Accesibilidad</a>
                    <a href="">Condiciones</a>
                </div>

                <div class="d-flex justify-content-between social_networks">
                    <a href=""><i class="fab fa-facebook-f"></i></a>
                    <a href=""><i class="fab fa-twitter"></i></a>
                    <a href=""><i class="fab fa-youtube"></i></a>
                    <a href=""><i class="fab fa-linkedin"></i></a>
                    <a href=""><i class="fab fa-instagram"></i></a>
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