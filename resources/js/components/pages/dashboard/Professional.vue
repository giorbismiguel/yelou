<template>
    <section class="row">
        <div class="thumbnail">
            <img src="/img/slide_inferior.png" alt="La profesionalidad es nuestra maxima a diario" class="img-fluid">
            <div class="caption">
                <h3>La profesionalidad es nuestra máxima a diario</h3>

                <p class="ml-5">
                    La seguridad de nuestros clientes es el principal objetivo de nuestra empresa.
                    Ellos lo agradecen confiando en la calidad del servicio.
                </p>
            </div>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Professional"
    }
</script>

<style scoped>
    section {
        width: 100%;
    }

    .thumbnail {
        position: relative;
    }

    .caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .caption h3 {
        color: rgb(255, 255, 255);
        font-family: Montserrat-Bold, sans-serif;
        font-size: 2.2em;
        font-weight: bold;
        height: 78px;
        text-align: center;
        width: 453px;
    }

    .caption p {
        width: 356px;
        height: 76px;
        color: rgb(255, 255, 255);
        font-size: 1em;
        font-family: 'Montserrat', sans-serif;
        font-weight: normal;
        text-align: center;
        margin-left: .5em;
    }
</style>