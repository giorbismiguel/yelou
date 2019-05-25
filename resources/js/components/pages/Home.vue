<template>
    <section>
        <carousel></carousel>

        <div class="row">
            <div class="col d-flex">
                <div class="card_auth d-flex align-items-center justify-content-around flex-colum">
                    <div class="d-flex flex-column card_auth_register">
                        <span>Quieres conducir?</span>
                        <strong>Regístrate</strong>
                    </div>
                    <div class="fontawesome">
                        <i class="fas fa-3x fa-car"></i>
                    </div>
                </div>
            </div>

            <div class="col">
                <div class="card_auth d-flex align-items-center justify-content-around flex-colum">
                    <div class="d-flex flex-column card_auth_register">
                        <span>Viajar seguro?</span>
                        <strong>Regístrate</strong>
                    </div>
                    <div class="fontawesome">
                        <i class="fas fa-3x fa-route"></i>
                    </div>
                </div>
            </div>

        </div>
    </section>
</template>

<script>
    import Carousel from './dashboard/Carousel'

    export default {
        name: "Home",
        components: {
            Carousel
        }
    }
</script>

<style scoped>
    .card_auth {
        border-radius: 8px 8px 8px 8px;
        border: 1px solid rgb(0, 66, 100);
        height: 116px;
        width: 447px;
        margin: 40px;
    }

    .card_auth .fontawesome {
        color: rgb(0, 66, 100);
    }

    .card_auth_register {
        width: 311px;
        color: rgb(0, 66, 100);
        font-weight: normal;
    }

    .card_auth_register span{
        font-size: 32px;
        font-family: 'Montserrat';
    }
</style>