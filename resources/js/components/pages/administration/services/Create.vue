<template>
    <box-user>
        <loading :active.sync="loadingView"
                 :can-cancel="true"
                 :is-full-page="true"></loading>

        <div class="row">
            <div class="col-12">
                <header-form>Servicio</header-form>
            </div>

            <div class="col-12 col-md-6">
                <div class="card app_card">
                    <div class="card-header">Solicitar Servicio</div>
                    <div class="card-body">
                        <form class="form-horizontal" id="new_route_form" role="form" autocomplete="off"
                              @submit.prevent="onSubmit">

                            <div class="form-group">
                                <label for="route">Ruta Favorita</label>

                                <ye-select id="route" name="route" v-model="form.route_id"
                                           :options="lists.userRoutes" placeholder="Seleccionar"
                                           :reduce="route => route.id" label="name"></ye-select>
                            </div>

                            <div class="input-group form-group">
                                <gmap-autocomplete class="form-control" id="origen_request_services" ref="origen"
                                                   name="origen_request_services" :value="form.name_start"
                                                   @place_changed="setOriginRequestServices"
                                                   :placeholder="writeLocationText"
                                                   @keypress.enter="$event.preventDefault()"
                                                   :class="{ 'is-invalid': submitted && (serverErrors.lat_start || serverErrors.lng_start) }">
                                </gmap-autocomplete>

                                <div class="input-group-prepend">
                                    <span @click="showOrigin" class="input-group-text">
                                        <i class="fas fa-search-location"></i>
                                    </span>
                                </div>

                                <div v-if="submitted && (serverErrors.lat_start || serverErrors.lng_start)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.lat_start">{{ error }}</template>
                                </div>
                            </div>

                            <div class="input-group form-group">
                                <gmap-autocomplete class="form-control" id="destination_request_services"
                                                   name="destination_request_services" :value="form.name_end"
                                                   @place_changed="setDestinationRequestServices"
                                                   placeholder="¿ A dónde vas?"
                                                   @keypress.enter="$event.preventDefault()"
                                                   :class="{ 'is-invalid': submitted && (serverErrors.lat_end || serverErrors.lng_end) }">
                                </gmap-autocomplete>

                                <div class="input-group-prepend">
                                    <span @click="showDestiny" class="input-group-text">
                                        <i class="fas fa-search-location"></i>
                                    </span>
                                </div>

                                <div v-if="submitted && (serverErrors.lat_end || serverErrors.lng_end)"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.lat_end">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-group" v-show="!form.route_id">
                                <div class="custom-control custom-checkbox">
                                    <input v-model="form.favourite" type="checkbox" class="custom-control-input"
                                           id="favourite"/>
                                    <label class="custom-control-label" for="favourite">Marcar como Favorita</label>
                                </div>
                            </div>

                            <p class="font-weight-light app_color_blue">
                                <a href="javascript:void(0);" @click="toggleCalendar">Planificar Horario</a>
                            </p>

                            <div v-show="showCalendar">
                                <div class="form-group">
                                    <label for="start_date">Día</label>

                                    <date-picker id="start_date" name="start_date" v-model="defaultDate"
                                                 style="width: 300px; display: block;" value-type="date"
                                                 :not-before="timePicker.notBefore"
                                                 :lang="timePicker.lang" type="date" :format="timePicker.date" confirm
                                                 confirm-text="Confirmar"
                                                 :input-class="[ 'form-control', submitted && serverErrors.start_date ? 'is-invalid': '']">
                                    </date-picker>

                                    <input type="text" class="form-control" v-show="false"
                                           :class="submitted && serverErrors.start_date ? 'is-invalid': ''"/>

                                    <div v-if="submitted && serverErrors.start_date"
                                         class="invalid-feedback">
                                        <template v-for="error in serverErrors.start_date">{{ error }}</template>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="start_time">Hora de Inicio</label>

                                    <date-picker id="start_time" name="start_time" v-model="defaultTime"
                                                 style="width: 300px; display: block;" :lang="timePicker.langTime"
                                                 type="time" :time-picker-options="timePicker.timePickerOption"
                                                 :format="timePicker.time" confirm confirm-text="Confirmar">
                                    </date-picker>

                                    <div v-if="submitted && serverErrors.start_time"
                                         class="invalid-feedback">
                                        <template v-for="error in serverErrors.start_time">{{ error }}</template>
                                    </div>
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
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'services' }" tag="button" type="button"
                                                 class="btn btn-cancel mr-4">
                                        Cancelar
                                    </router-link>

                                    <button @keyup.enter="onSubmit" type="submit" class="btn btn-accept ml-4"
                                            :disabled="loading">
                                        Solicitar Servicio
                                    </button>

                                    <spinner v-if="loading" size="medium" class="ml-2"></spinner>

                                    <button type="button" class="btn btn-cancel ml-5" @click="calculate"
                                            v-show="originAndSourceActive">
                                        Calcular Tarifa
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <ye-modal id="" :title="modal.title" :show="modal.show" size="large" cancel-text="Cerrar"
                  @cancel="hideMarkers" @ok="saveMarker" ok-text="Aceptar">
            <div class="row">
                <div class="col-md-12">
                    <GmapMap :center="centerMarker" :zoom="15" style="width: 100%; height: 70vh;">
                        <GmapMarker v-for="(m, index) in markers"
                                    :position="m.position"
                                    @dragend="updateCoordinates"
                                    :clickable="true" :draggable="true"
                                    @click="centerMarker=m.position"
                                    :key="index">
                        </GmapMarker>
                    </GmapMap>

                    <div class="input-group mb-3">
                        <gmap-autocomplete class="form-control" id="address" name="address"
                                           ref="address" :value="formatAddress"
                                           @place_changed="setDestinationToMap" placeholder="¿ A dónde vas?"
                                           @keypress.enter="$event.preventDefault()">
                        </gmap-autocomplete>
                    </div>
                </div>
            </div>
        </ye-modal>

        <notifications group="create_request_service" position="bottom right"/>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'
    import DatePicker from 'vue2-datepicker'
    import HeaderForm from '../layout/header_form'
    import Swal from 'sweetalert2'
    import navigator from '../../../../mixins/navigator'
    import Loading from 'vue-loading-overlay';

    export default {
        name: "CreateService",

        mixins: [navigator],

        data() {
            return {
                loadingView: true,
                form: {
                    id: null,
                    route_id: null,
                    name_start: null,
                    lat_start: null,
                    lng_start: null,
                    name_end: null,
                    lat_end: null,
                    lng_end: null,
                    start_date: null,
                    start_time: null,
                    payment_method_id: null,
                    favourite: 0
                },
                formRate: {
                    latitude_from: null,
                    longitude_from: null,
                    latitude_to: null,
                    longitude_to: null
                },
                defaultDate: new Date(),
                defaultTime: null,
                markers: [],
                centerMarker: {lat: -0.180653, lng: -78.467834},
                modal: {
                    title: '',
                    show: false
                },
                timePicker: {
                    lang: {
                        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                        pickers: ['next 7 days', 'next 30 days', 'previous 7 days', 'previous 30 days'],
                        placeholder: {
                            date: 'Seleccione el día'
                        }
                    },
                    langTime: {
                        placeholder: {
                            date: 'Seleccione la hora'
                        }
                    },
                    date: 'DD/MM/YYYY',
                    time: 'hh:mm:ss',
                    notBefore: new Date(),
                    timePickerOption: {
                        start: DatePicker.fecha.format(new Date(), 'HH:mm:ss'),
                        step: DatePicker.fecha.format(new Date(), '00:01'),
                        end: '23:59:59'
                    }
                },
                submitted: false,
                loading: false,

                isSelectingOrigin: false,
                coordinatesOrigin: null,

                isSelectingDestiny: false,
                coordinatesDestiny: null,
                formatAddress: null,
                placeholderCurrentLocation: 'Ubicación actual',
                defaultNameOrigin: 'Ubicación actual',
                defaultNameDestiny: 'Destino seleccionado',
                writeLocationText: 'Escribe la ubicación actual',
                currentLocation: true,
                originRequestService: null,
                destinationRequestService: null,
                serverErrors: {},
                route: null,
                showCalendar: false
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                lists: state => state.nomenclators.listsRequestServices
                    ? state.nomenclators.listsRequestServices
                    : {'paymentMethods': [], 'userRoutes': []},
                rate: state => state.general.rate,
                requestService: state => state.requestServices.requestService
            }),

            originAndSourceActive() {
                return (
                    this.coordinatesOrigin &&
                    !this.route &&
                    !this.originRequestService &&
                    this.destinationRequestService)
                    ||
                    (this.originRequestService && this.destinationRequestService)
                    ||
                    (this.coordinatesOrigin && this.coordinatesDestiny)
                    ||
                    this.route
            }
        },

        methods: {
            ...mapActions([
                'nomenclatorsRequestServices',
                'createRequestService',
                'calculateRate',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                        if (valid) {
                            if (this.form.id) {
                                this.createOrResendService();
                            } else {
                                Swal.fire({
                                    title: 'Se encuentra en el punto de partida?',
                                    type: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Si, Continuar',
                                    cancelButtonText: 'No, volver a definirlo'
                                }).then((result) => {
                                    if (result.value) {
                                        this.createOrResendService();

                                        return;
                                    }

                                    this.showOrigin()
                                })
                            }
                        }
                    }
                );
            },

            createOrResendService() {
                this.loadingView = true
                this.serverErrors = {}

                if (this.coordinatesOrigin && !this.route && !this.originRequestService) {
                    this.form.lat_start = this.coordinatesOrigin.lat
                    this.form.lng_start = this.coordinatesOrigin.lng
                    this.form.name_start = this.form.name_start ? this.form.name_start : this.defaultNameOrigin
                } else if (this.originRequestService) {
                    this.form.lat_start = this.originRequestService.geometry.location.lat()
                    this.form.lng_start = this.originRequestService.geometry.location.lng()
                    this.form.name_start = this.originRequestService.formatted_address
                }

                if (this.coordinatesDestiny && !this.route && !this.originRequestService) {
                    this.form.lat_end = this.coordinatesDestiny.lat
                    this.form.lng_end = this.coordinatesDestiny.lng
                    this.form.coordinatesDestiny = this.form.name_end ? this.form.name_end : this.defaultNameDestiny
                } else if (this.destinationRequestService) {
                    this.form.lat_end = this.destinationRequestService.geometry.location.lat()
                    this.form.lng_end = this.destinationRequestService.geometry.location.lng()
                    this.form.name_end = this.destinationRequestService.formatted_address
                }

                if (this.showCalendar) {
                    this.form.start_date = DatePicker.fecha.format(new Date(this.defaultDate), 'DD/MM/YYYY')
                }

                if (this.showCalendar && this.defaultTime) {
                    this.form.start_time = DatePicker.fecha.format(new Date(this.defaultTime), 'HH:mm:ss')
                }

                this.form.favourite = this.form.favourite ? 1 : 0;
                this.createRequestService(this.form)
                    .then(() => {
                        this.form.id = this.requestService.id

                        setTimeout(() => {
                            this.$notify({
                                group: 'create_request_service',
                                title: 'Ruta',
                                text: 'El servicio ha sido creado pero no ha recibido ninguna respuesta, vuelva intentarlo por favor.',
                                duration: 10000
                            });

                            this.loadingView = false
                        }, 30000);
                    })
                    .catch((data) => {
                        this.loadingView = false

                        if (data.success === false) {
                            Swal.fire({
                                text: data.message,
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonText: 'Aceptar',
                            }).then(() => {
                                this.$router.replace('/servicios')
                            })

                            this.serverErrors = data.errors || {}

                            return;
                        }


                        if (data.errors) {
                            this.serverErrors = data.errors || {}

                            return;
                        }

                        this.$notify({
                            type: 'error',
                            group: 'create_request_service',
                            title: 'Ruta',
                            text: 'Ha ocurrido un error al realizar la solicitud del servicio.'
                        });
                    })
            },

            setDestinationToMap(place) {
                this.formatAddress = place.formatted_address

                this.centerMarker = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()}
                this.markers[0] = {position: this.centerMarker}

                if (this.isSelectingOrigin) {
                    this.form.name_start = place.formatted_address
                    this.originRequestService = place
                } else {
                    this.form.name_end = place.formatted_address
                    this.destinationRequestService = place
                }
            },

            setOriginRequestServices(place) {
                this.originRequestService = place
                this.form.name_start = place.formatted_address
                this.formatAddress = place.formatted_address

                this.centerMarker = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()}
                this.markers[0] = {position: this.centerMarker}
            },

            setDestinationRequestServices(place) {
                this.destinationRequestService = place
                this.form.name_end = place.formatted_address
                this.formatAddress = place.formatted_address
                this.centerMarker = {lat: place.geometry.location.lat(), lng: place.geometry.location.lng()}
                this.markers[0] = {position: this.centerMarker}
            },

            changeCurrentLocation() {
                this.currentLocation = !this.currentLocation
                this.placeholderCurrentLocation = this.currentLocation ? this.defaultNameOrigin : ''
            },

            calculate() {
                this.calculateRate(this.formRate)
                    .then(() => {
                        if (this.rate.success) {
                            Swal.fire({
                                text: this.rate.message,
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonText: 'Aceptar',
                            })
                        }
                    })
                    .catch((data) => {
                        this.serverErrors = data.errors || {}
                    })
            },

            showOrigin() {
                this.formatAddress = this.form.name_start ? this.form.name_start : null

                this.modal.title = 'Punto de Origen'
                this.modal.show = true
                this.isSelectingOrigin = true
            },

            showDestiny() {
                this.formatAddress = this.form.name_end ? this.form.name_end : null

                this.modal.title = 'Punto de Destino'
                this.modal.show = true
                this.isSelectingDestiny = true
            },

            hideMarkers() {
                this.modal.show = false
                if (this.isSelectingOrigin) {
                    this.isSelectingOrigin = false
                }

                if (this.isSelectingDestiny) {
                    this.isSelectingDestiny = false
                }
            },

            saveMarker() {
                this.modal.show = false
                if (this.isSelectingOrigin) {
                    this.form.name_start = this.formatAddress
                    this.isSelectingOrigin = false
                }

                if (this.isSelectingDestiny) {
                    this.form.name_end = this.formatAddress
                    this.isSelectingDestiny = false
                }

                this.formatAddress = null
            },

            updateCoordinates(location) {
                if (this.isSelectingOrigin) {
                    this.originRequestService = null;
                    this.coordinatesOrigin = {
                        lat: location.latLng.lat(),
                        lng: location.latLng.lng(),
                    }
                } else {
                    this.destinationRequestService = null
                    this.coordinatesDestiny = {
                        lat: location.latLng.lat(),
                        lng: location.latLng.lng(),
                    }
                }

                this.geocodedAddress(this.isSelectingOrigin ? this.coordinatesOrigin : this.coordinatesDestiny);
            },

            geocodedAddress(coordinates) {
                let geocoder = new google.maps.Geocoder()
                geocoder.geocode({'latLng': coordinates}, (result, status) => {
                    if (status === google.maps.GeocoderStatus.OK) {
                        this.formatAddress = result[0].formatted_address
                    }
                })
            },

            geocodeAddressStart(coordinates) {
                let geocoder = new google.maps.Geocoder()
                geocoder.geocode({'latLng': coordinates}, (result, status) => {
                    if (status === google.maps.GeocoderStatus.OK) {
                        this.form.name_start = result[0].formatted_address
                        this.formatAddress = result[0].formatted_address
                    }
                })
            },

            toggleCalendar() {
                this.showCalendar = !this.showCalendar
            },

            listenForRequestedServicesAccepted() {
                Echo.channel(`servicesAccepted.${this.me.id}`)
                    .listen('RequestedServicesAccepted', (e) => {
                        this.loadingView = false

                        Swal.fire({
                            title: 'Tiene choferes disponibles',
                            text: 'Vaya al listado de servicios y consulte los choferes que desean atender su solicitud.',
                            type: 'info',
                            showCancelButton: false,
                            confirmButtonText: 'Ir a listado',
                        }).then(() => {
                            this.$router.replace('/servicios')
                        })
                    });
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
            },

            coordinatesOrigin(coordinatesOrigin) {
                this.formRate.latitude_from = coordinatesOrigin.lat
                this.formRate.longitude_from = coordinatesOrigin.lat
            },

            coordinatesDestiny(coordinatesDestiny) {
                this.formRate.latitude_from = coordinatesDestiny.lat
                this.formRate.longitude_from = coordinatesDestiny.lat
            },

            originRequestService(originRequestService) {
                this.formRate.latitude_from = originRequestService.geometry.location.lat()
                this.formRate.longitude_from = originRequestService.geometry.location.lat()
            },

            destinationRequestService(destinationRequestService) {
                this.formRate.latitude_to = destinationRequestService.geometry.location.lat()
                this.formRate.longitude_to = destinationRequestService.geometry.location.lat()
            }
        },

        components: {
            DatePicker,
            Spinner,
            BoxUser,
            HeaderForm,
            Loading
        },

        async created() {
            this.nomenclatorsRequestServices()

            try {
                const {coords} = await this.getCurrentPositionUser()
                if (coords.latitude && coords.longitude) {
                    this.centerMarker = {lat: coords.latitude, lng: coords.longitude}
                    this.geocodeAddressStart(this.centerMarker)
                    this.coordinatesOrigin = this.centerMarker
                    this.markers.push({
                        position: this.centerMarker
                    })
                }
            } catch (e) {
                this.geocodeAddressStart(this.centerMarker)
                this.coordinatesOrigin = this.centerMarker
                this.markers.push({
                    position: this.centerMarker
                })
            }
        },

        mounted() {
            this.loadingView = false

            this.listenForRequestedServicesAccepted()
        }
    }
</script>
