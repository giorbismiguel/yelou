<template>
    <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="/img/slider/slide_1.png" class="d-block w-100" alt="">
            </div>
            <div class="carousel-item">
                <img src="/img/slider/slide_2.png" class="d-block w-100" alt="">
            </div>
            <div class="carousel-item">
                <img src="/img/slider/slide_3.png" class="d-block w-100" alt=".">
            </div>
            <div class="carousel-item">
                <img src="/img/slider/slide_4.png" class="d-block w-100" alt=".">
            </div>
            <div class="carousel-item">
                <img src="/img/slider/slide_5.png" class="d-block w-100" alt=".">
            </div>
            <div class="carousel-item">
                <img src="/img/slider/slide_6.png" class="d-block w-100" alt=".">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Anterior</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Siguiente</span>
        </a>
    </div>
</template>

<script>
    export default {
        name: "Carousel"
    }
</script>