<template>
    <div class="professional row">
        <div class="col">
            <div class="thumbnail">
                <img src="/img/slide_inferior.png" alt="La profesionalidad es nuestra maxima a diario"
                     class="img-fluid">
                <div class="caption">
                    <div class="header">La profesionalidad es nuestra máxima a diario</div>

                    <div class="body">
                        La seguridad de nuestros clientes es el principal objetivo de nuestra empresa. Ellos lo
                        agradecen confiando en la calidad del servicio.
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Professional"
    }
</script>

<style scoped>
    .thumbnail {
        position: relative;
    }

    .caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .caption .header {
        color: rgb(255, 255, 255);
        font-family: Montserrat-Bold, sans-serif;
        font-size: 2.2em;
        font-weight: bold;
        text-align: center;
    }

    .caption .body {
        color: rgb(255, 255, 255);
        font-family: 'Montserrat', sans-serif;
        font-size: 1em;
        font-weight: normal;
        text-align: center;
        margin-left: .5em;
    }

    @media (max-width: 991.98px) {
        .caption .header {
            font-size: 1rem;
            height: 8vh;
            width: 90vw;
        }

        .caption .body {
            width: 80vw;
            font-size: .7em;
        }
    }

    @media (max-width: 575.98px) {
        .caption .header {
            top: 60%;
        }
    }
</style>