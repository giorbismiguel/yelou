<template>
    <box-user>
        <div v-if="loadingView" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>
    </box-user>
</template>

<script>
    import {mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'

    export default {
        name: "Accept",

        data() {
            return {
                form: {
                    service_id: null,
                    driver_id: null,
                },
                loadingView: true
            }
        },

        methods: {
            ...mapActions([
                'acceptRequestedService'
            ]),

            processRequestedService() {
                this.acceptRequestedService(this.form)
                    .then(() => {
                        this.$notify({
                            type: 'success',
                            group: 'create_request_service',
                            title: 'Ruta',
                            text: 'La solicitud del servicio ha sido exitosa'
                        });
                    })
                    .catch((data) => {
                        console.log(data)

                        this.$notify({
                            type: 'error',
                            group: 'create_request_service',
                            title: 'Ruta',
                            text: 'Ha ocurrido un error al realizar la solicitud del servicio.'
                        });
                    })
            },
        },

        components: {
            Spinner,
            BoxUser,
        },

        mounted() {
            this.form.service_id = this.$route.params.service
            this.form.driver_id = this.$route.params.driver

            this.processRequestedService()
        }
    }
</script>

<style scoped>

</style>