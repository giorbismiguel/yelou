<template>
    <box-user>
        <div v-if="loadingView" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'
    import Swal from 'sweetalert2'

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

        computed: {
            ...mapState({
                responseRequested: state => state.requestServices.responseRequested
            }),
        },

        methods: {
            ...mapActions([
                'acceptRequestedService'
            ]),

            processRequestedService() {
                this.acceptRequestedService(this.form)
                    .then(() => {
                        this.loadingView = false
                        Swal.fire({
                            text: 'La solicitud del servicio ha sido exitosa',
                            type: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Aceptar',
                        }).then(() => {
                            this.$router.replace('/administracion')
                        })
                    })
                    .catch(data => {
                        this.loadingView = false
                        if (!data.success) {
                            Swal.fire({
                                text: data.message,
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonText: 'Aceptar',
                            }).then(() => {
                                this.$router.replace('/administracion')
                            })

                            return;
                        }

                        Swal.fire({
                            text: data.message,
                            type: 'error',
                            showCancelButton: false,
                            confirmButtonText: 'Aceptar',
                        }).then(() => {
                            this.$router.replace('/administracion')
                        })
                    })
            },
        },

        components: {
            Spinner,
            BoxUser,
            Swal
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