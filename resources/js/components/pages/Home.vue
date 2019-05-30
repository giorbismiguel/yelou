<template>
    <section>
        <carousel></carousel>

        <register-as v-if="me"></register-as>
    </section>
</template>

<script>
    import Carousel from './dashboard/Carousel'
    import RegisterAs from "../pages/auth/RegisterAs"
    import {mapState} from 'vuex'

    export default {
        name: "Home",

        components: {
            Carousel,
            RegisterAs
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
        },

    }
</script>