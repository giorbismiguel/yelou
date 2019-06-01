<template>
    <section class="container">

        <carousel></carousel>

        <services></services>

        <professional></professional>

        <register-as v-if="!me"></register-as>

    </section>
</template>

<script>
    import Carousel from './dashboard/Carousel'
    import Services from './dashboard/Services'
    import Professional from './dashboard/Professional'
    import RegisterAs from "../pages/auth/RegisterAs"
    import {mapState} from 'vuex'

    export default {
        name: "Home",

        components: {
            Carousel,
            Services,
            Professional,
            RegisterAs,
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
        },

    }
</script>