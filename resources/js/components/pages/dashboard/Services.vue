<template>
    <section>
        <div class="caption text-center">
            <h3>Más de 230 ciudades <br/> disfrutan de nuestros servicios</h3>

            <button class="btn app_btn-info">
                <img src="/img/city.png" alt="Ciudad" /> Explorar
            </button>
        </div>
    </section>
</template>

<script>
    export default {
        name: "Services"
    }
</script>

<style scoped>
    section {
        width: 1116px;
        height: 406px;
        background: rgb(245, 252, 255);
        border-radius: 0px 0px 0px 0px;
        background-image: url("../../../../img/illustration.png");
        background-repeat: repeat-x;
        background-color: #F5FCFF;
        position: relative;
    }

    .caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-family: 'Montserrat', sans-serif;
    }
</style>