<template>
    <box-user>
        <h3>Servicio</h3>
        <hr>

        <div v-if="loadingView" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>

        <div v-else class="row justify-content-center">
            <div class="col-6">
                <div class="card m-4">
                    <div class="card-header">Nuevo Servicio</div>
                    <div class="card-body">
                        <form class="form-horizontal" id="new_route_form" role="form" autocomplete="off"
                              @submit.prevent="onSubmit">

                            <div class="form-group">
                                <label for="route">Rutas</label>
                                <ye-select id="route" name="route" v-model="form.route_id"
                                           :options="lists.userRoutes"
                                           :reduce="route => route.id" label="name"></ye-select>
                            </div>

                            <div class="form-group">
                                <label for="start_time">Hora de Inicio<span class="text-danger">*</span></label>
                                <date-picker id="start_time" name="start_time" v-model="form.start_time"
                                             style="width: 300px; display: block;" input-class="form-control"
                                             :lang="timePicker.lang" type="datetime" format="YYYY-MM-DD hh:mm:ss"
                                             value-type="format" confirm confirm-text="Confirmar"
                                             :input-class="[ 'form-control', submitted && (serverErrors.start_time || serverErrors.start_time) ? 'is-invalid': '']">
                                </date-picker>

                                <div class="form-control" v-if="false"></div>
                                <div v-if="submitted && (serverErrors.start_time || serverErrors.start_time)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.start_time">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <label for="place_origen">Origen<span class="text-danger">*</span></label>
                                <gmap-autocomplete class="form-control" id="place_origen" name="place_origen"
                                                   @place_changed="setPlaceOrigin"
                                                   :class="{ 'is-invalid': submitted && (serverErrors.lat_start || serverErrors.lng_start) }">
                                </gmap-autocomplete>

                                <div v-if="submitted && (serverErrors.lat_start || serverErrors.lng_start)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.lat_start">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="place_destination">Destino<span class="text-danger">*</span></label>
                                <gmap-autocomplete class="form-control" id="place_destination"
                                                   name="place_destination" @place_changed="setPlaceDestination"
                                                   :class="{ 'is-invalid': submitted && (serverErrors.lat_end || serverErrors.lng_end) }">
                                </gmap-autocomplete>
                                <div v-if="submitted && (serverErrors.lat_end || serverErrors.lng_end)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.lat_end">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_method">Medio de pago</label>
                                <ye-select id="payment_method" name="payment_method" v-model="form.payment_method_id"
                                           :options="lists.paymentMethods"
                                           :class="{ 'is-invalid': submitted && (serverErrors.payment_method_id || serverErrors.payment_method_id) }"
                                           :reduce="method => method.id" label="name"></ye-select>
                                <div v-if="submitted && (serverErrors.payment_method_id || serverErrors.payment_method_id)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.payment_method_id">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-end">
                                    <router-link :to="{ name: 'services' }" tag="button" class="btn btn-light mr-4">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-primary ml-4" :disabled="loading">
                                        Adicionar
                                    </button>
                                    <spinner v-if="loading" size="medium" class="ml-2"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="create_request_service"/>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'
    import DatePicker from 'vue2-datepicker'

    export default {
        name: "Create",

        data() {
            return {
                loadingView: true,
                form: {
                    route_id: null,
                    name_start: null,
                    lat_start: null,
                    lng_start: null,
                    name_end: null,
                    lat_end: null,
                    lng_end: null,
                    start_time: null,
                    payment_method_id: null
                },
                timePicker: {
                    lang: {
                        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                        pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                        placeholder: {
                            date: 'Seleccione el dia'
                        }
                    }
                },
                submitted: false,
                loading: false,
                placeOrigin: null,
                placeDestination: null,
                serverErrors: {}
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                lists: state => state.nomenclators.listsPaymentMethod
                    ? state.nomenclators.listsPaymentMethod
                    : {'paymentMethods': [], 'userRoutes': []},
            }),
        },

        methods: {
            ...mapActions([
                'nomenclatorsPaymentMethod',
                'createRequestService',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        this.serverErrors = {}

                        if (this.placeOrigin) {
                            this.form.lat_start = this.placeOrigin.geometry.location.lat()
                            this.form.lng_start = this.placeOrigin.geometry.location.lng()
                            this.form.name_start = this.placeOrigin.formatted_address
                        }

                        if (this.placeDestination) {
                            this.form.lat_end = this.placeDestination.geometry.location.lat()
                            this.form.lng_end = this.placeDestination.geometry.location.lng()
                            this.form.name_end = this.placeDestination.formatted_address
                        }

                        this.createRequestService(this.form)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'create_request_service',
                                    title: 'Ruta',
                                    text: 'La solicitud del servicio ha sido exitosa'
                                });

                                this.$router.replace('/servicios')
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'create_request_service',
                                    title: 'Ruta',
                                    text: 'Ha ocurrido un error al realizar la solicitud del servicio.'
                                });
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

            setPlaceOrigin(place) {
                this.placeOrigin = place
            },

            setPlaceDestination(place) {
                this.placeDestination = place
            }
        },

        components: {
            DatePicker,
            Spinner,
            BoxUser
        },

        created() {
            this.nomenclatorsPaymentMethod();
        },

        mounted() {
            this.loadingView = false
        }
    }
</script>
