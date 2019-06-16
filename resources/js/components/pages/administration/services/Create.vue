<template>
    <box-user>
        <div v-if="loadingView" class="d-flex justify-content-center mt-5">
            <spinner size="large"></spinner>
        </div>

        <div v-else class="row">
            <div class="col-12">
                <header-form>Servicio</header-form>
            </div>

            <div class="col-8">
                <div class="card app_card">
                    <div class="card-header">Solicitar Servicio</div>
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
                                <label for="start_time">Hora de Inicio<span class="text-primary">*</span></label>

                                <date-picker id="start_time" name="start_time" v-model="universalTime"
                                             style="width: 300px; display: block;" value-type="date"
                                             :lang="timePicker.lang" type="datetime" :format="timePicker.format"
                                             confirm confirm-text="Confirmar"
                                             :input-class="[ 'form-control', submitted && serverErrors.start_time ? 'is-invalid': '']">
                                </date-picker>

                                <input type="text" class="form-control" v-show="false"
                                       :class="submitted && serverErrors.start_time ? 'is-invalid': ''"/>

                                <div v-if="submitted && serverErrors.start_time"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.start_time">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <label for="origen_request_services">Origen<span class="text-primary">*</span></label>

                                <input v-model="form.name_start" :placeholder="placeholderCurrentLocation"
                                       v-if="currentLocation" id="actual_ubication" name="actual_ubication" type="text"
                                       class="form-control"/>

                                <gmap-autocomplete v-else class="form-control" id="origen_request_services" ref="origen"
                                                   name="origen_request_services" :value="form.name_start"
                                                   @place_changed="setOrigenRequestServices"
                                                   :placeholder="writeLocationText" @keypress.enter="$event.preventDefault()"
                                                   :class="{ 'is-invalid': submitted && (serverErrors.lat_start || serverErrors.lng_start) }">
                                </gmap-autocomplete>

                                <button type="button" class="btn btn-accept btn-sm mt-2"
                                        @click="changeCurrentLocation">
                                    Escribir el origen
                                </button>

                                <div v-if="submitted && (serverErrors.lat_start || serverErrors.lng_start)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.lat_start">{{ error }}</template>
                                </div>

                            </div>

                            <div class="form-group">
                                <label for="destination_request_services">Destino<span
                                        class="text-primary">*</span></label>

                                <gmap-autocomplete class="form-control" id="destination_request_services"
                                                   name="destination_request_services" :value="form.name_end"
                                                   @place_changed="setDestinationRequestServices"
                                                   placeholder="¿ A dónde vas?" @keypress.enter="$event.preventDefault()"
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

                            <div class="form-group" v-show="!form.route_id">
                                <div class="custom-control custom-checkbox">
                                    <input v-model="form.favourite" type="checkbox" class="custom-control-input"
                                           id="favourite"/>
                                    <label class="custom-control-label" for="favourite">Favorita</label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'services' }" tag="button" class="btn btn-cancel mr-4">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-accept ml-4" :disabled="loading">
                                        Solicitar Servicio
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
    import HeaderForm from '../layout/header_form'

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
                    payment_method_id: null,
                    favourite: 0,
                    today_time: null
                },
                universalTimeNow: new Date(),
                universalTime: new Date(new Date().getTime() + 10 * 60000),
                timePicker: {
                    lang: {
                        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                        pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                        placeholder: {
                            date: 'Seleccione el dia'
                        }
                    },
                    format: 'DD/MM/YYYY hh:mm:ss'
                },
                submitted: false,
                loading: false,
                currentLocationLatLng: null,
                placeholderCurrentLocation: 'Ubicación actual',
                currentLocationText: 'Ubicación actual',
                writeLocationText: 'Escribe la ubicación actual',
                currentLocation: true,
                originRequestService: null,
                destinationRequestService: null,
                serverErrors: {},
                route: null
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                lists: state => state.nomenclators.listsRequestServices
                    ? state.nomenclators.listsRequestServices
                    : {'paymentMethods': [], 'userRoutes': []},
            }),
        },

        methods: {
            ...mapActions([
                'nomenclatorsRequestServices',
                'createRequestService',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                        if (valid) {
                            this.loading = true
                            this.serverErrors = {}

                            if (this.currentLocationLatLng && !this.route && !this.originRequestService) {
                                this.form.lat_start = this.currentLocationLatLng.lat
                                this.form.lng_start = this.currentLocationLatLng.lng
                                this.form.name_start = this.form.name_start ? this.form.name_start : this.currentLocationText
                            } else if (this.originRequestService) {
                                this.form.lat_start = this.originRequestService.geometry.location.lat()
                                this.form.lng_start = this.originRequestService.geometry.location.lng()
                                this.form.name_start = this.originRequestService.formatted_address
                            }

                            if (this.destinationRequestService) {
                                this.form.lat_end = this.destinationRequestService.geometry.location.lat()
                                this.form.lng_end = this.destinationRequestService.geometry.location.lng()
                                this.form.name_end = this.destinationRequestService.formatted_address
                            }

                            this.form.start_time = DatePicker.fecha.format(new Date(this.universalTime), 'DD/MM/YYYY HH:mm:ss')
                            this.form.today_time = DatePicker.fecha.format(new Date(this.universalTimeNow), 'DD/MM/YYYY HH:mm:ss')
                            this.form.favourite = this.form.favourite ? 1 : 0;
                            this.createRequestService(this.form)
                                .then(() => {
                                    this.loading = false
                                    this.$notify({
                                        type: 'success',
                                        group: 'create_request_service',
                                        title: 'Ruta',
                                        text: 'La solicitud del servicio ha sido exitosa'
                                    });

                                    //this.$router.replace('/servicios')
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
                    }
                )
                ;
            },

            setOrigenRequestServices(place) {
                this.originRequestService = place
                this.form.name_start = place.formatted_address
            },

            setDestinationRequestServices(place) {
                this.destinationRequestService = place
                this.form.name_end = place.formatted_address
            },

            getCurrentPositionUser() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        this.currentLocationLatLng = {lat: position.coords.latitude, lng: position.coords.longitude}
                    });
                }
            },

            changeCurrentLocation() {
                this.currentLocation = !this.currentLocation
                this.placeholderCurrentLocation = this.currentLocation ? this.currentLocationText : ''
            }
        },

        watch: {
            'form.route_id': function (id) {
                if (id) {
                    let route = this.lists.userRoutes.filter((route) => {
                        return route.id === id;
                    });

                    this.route = route[0]
                    this.originRequestService = null
                    this.destinationRequestService = null

                    if (this.route) {
                        this.form.name_start = this.route.formatted_address_start
                        this.form.lat_start = this.route.lat_start
                        this.form.lng_start = this.route.lng_start
                        this.form.name_end = this.route.formatted_address_end
                        this.form.lat_end = this.route.lat_end
                        this.form.lng_end = this.route.lng_end
                    }

                    return;
                }

                this.route = null;
                this.form.name_start = null
                this.form.lat_start = null
                this.form.lng_start = null
                this.form.name_end = null
                this.form.lat_end = null
                this.form.lng_end = null
                this.favourite = 0;
            }
        },

        components: {
            DatePicker,
            Spinner,
            BoxUser,
            HeaderForm
        },

        created() {
            this.nomenclatorsRequestServices();
        },

        mounted() {
            this.getCurrentPositionUser()
            this.loadingView = false
        }
    }
</script>
