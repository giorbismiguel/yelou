<template>
    <section>
        <carousel></carousel>

        <register-as></register-as>
    </section>
</template>

<script>
    import Carousel from './dashboard/Carousel'
    import RegisterAs from "../pages/auth/RegisterAs";

    export default {
        name: "Home",
        components: {
            Carousel,
            RegisterAs
        }
    }
</script>