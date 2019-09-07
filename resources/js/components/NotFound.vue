<template>
    <box-user>
        <div class="row text-center">
            <div class="col-12">
                <header-form>Error 404</header-form>
            </div>
            <div class="col-12">
                Página no encontrada
            </div>
        </div>
    </box-user>
</template>

<script>
    import BoxUser from './layout/BoxUser'
    import HeaderForm from './pages/administration/layout/header_form'

    export default {

        data() {
            return {}
        },

        components: {
            BoxUser,
            HeaderForm
        }
    }
</script>
