<template>
    <div v-cloak>
        <div v-if="generalLoading" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>

        <template v-else>
            <header>
                <navbar></navbar>
            </header>
            <main role="main">
                <router-view></router-view>

                <feet></feet>
            </main>
        </template>
    </div>
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

