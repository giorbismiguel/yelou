<template>
    <section>
        <div v-if="generalLoading" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>

        <template v-else v-cloak>
            <navbar></navbar>

            <router-view></router-view>

            <feet></feet>
        </template>
    </section>
</template>

<script>
    import Spinner from 'vue-simple-spinner'
    import {mapState} from 'vuex'

    export default {
        name: "App",

        computed: {
            ...mapState({
                generalLoading: state => state.general.generalLoading,
            })
        },

        components: {
            Spinner
        },
    }
</script>

