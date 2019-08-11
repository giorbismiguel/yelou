<template>
    <box-user>
        <template v-if="me && me.type === 1">
            <div class="col-12">
                <header-form>Choferes disponibles</header-form>
            </div>

            <div class="row mb-2">
                <div class="col-6">
                    <gmap-autocomplete class="form-control" @place_changed="changePlace"
                                       @keypress.enter="$event.preventDefault()"
                                       placehoder="Escriba su ubicación para ver los choferes mas cercanos">
                    </gmap-autocomplete>
                </div>

                <div class="col-6">
                    <div class="col text-right">
                        <router-link tag="button" class="btn btn-accept" :to="{name: 'services_create'}">
                            Solicitar Servicio
                        </router-link>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    <GmapMap
                            :center="latLngClient"
                            :zoom="15"
                            map-type-id="terrain"
                            style="width: 100%; min-height:80vh;">
                        <GmapInfoWindow
                                :options="infoOptions"
                                :position="infoWindowPos"
                                :opened="infoWinOpen"
                                @closeclick="infoWinOpen=false">
                            {{ infoContent }}
                        </GmapInfoWindow>

                        <GmapMarker
                                :key="index"
                                v-for="(m, index) in drivers"
                                :position="m.position"
                                :clickable="true"
                                :title="m.infoText"
                                @click="toggleInfoWindow(m,index)"
                        />
                    </GmapMap>
                </div>
            </div>
        </template>

        <template v-else>
            <h3>Administración</h3>
            <hr>

            <div class="d-flex justify-content-end">
                <toggle-button @change="onChangeStatusDriver" :labels="{checked: 'Activo', unchecked: 'Inactivo'}"
                               :width="85" :height="30" :value="stateDriver" :sync="stateDriver"/>
            </div>

            <div class="alert alert-success" role="alert" v-if="messageServiceAcceptedByClient">
                <h4 class="alert-heading">Servicio Aceptado</h4>
                <p>{{ messageServiceAcceptedByClient }}</p>
                <hr>
                <p class="mb-0">Consulte el mapa para el punto de inicio</p>
            </div>

            <ye-table id="table_requested_services"
                      :columns="columns"
                      :options="options"
                      :data="requestServices"
                      :url="''"
                      :client-pagination="true"
                      ref="table">

                <template slot="table-title">Solicitudes para prestarle el servicio</template>

                <ye-actions slot="actions" slot-scope="{row}" class="text-center">
                    <li>
                        <button @click="acceptService(row)" class="dropdown-item" v-if="!servicesAccepted"
                           title="Quiero prestar el servicio" target="_blank">
                            <i class="fas fa-car-alt"></i>
                            Quiero prestar el servicio
                        </button>

                        <button @click="cancelService" class="dropdown-item" v-if="servicesAccepted"
                           title="Quiero prestar el servicio" target="_blank">
                            <i class="fas fa-car-alt"></i>
                            Cancelar el servicio
                        </button>
                    </li>
                </ye-actions>

                <template slot="filters-form"></template>
            </ye-table>
        </template>

        <notifications group="dashboard_request_service" position="center center"/>
    </box-user>
</template>

<script>
    import BoxUser from '../../layout/BoxUser'
    import {mapState, mapActions} from 'vuex'
    import HeaderForm from './layout/header_form'
    import navigator from '../../../mixins/navigator'
    import Spinner from 'vue-simple-spinner'
    import Swal from 'sweetalert2'

    export default {
        name: "Administration",

        mixins: [navigator],

        data() {
            return {
                infoContent: '',
                infoWindowPos: null,
                infoWinOpen: false,
                currentMidx: null,
                infoOptions: {
                    pixelOffset: {
                        width: 0,
                        height: -35
                    }
                },
                messageServiceAcceptedByClient: null,
                servicesAccepted: false,
                latLngClient: {lat: -0.180653, lng: -78.467834},
                stateDriver: false,
                requestServices: [],
                columns: [
                    'name',
                    'origin',
                    'destiny',
                    'payment',
                    'date',
                    'time',
                    'actions',
                ],

                options: {
                    columnsClasses: {
                        'actions': 'action-col'
                    },

                    headings: {
                        'name': 'Nombre',
                        'origin': 'Origen',
                        'destiny': 'Destino',
                        'payment': 'Medio de pago',
                        'date': 'Día',
                        'time': 'Hora de Inicio',
                        'actions': 'Acciones',
                    }
                }
            }
        },

        computed: {
            ...mapState({
                markers: drivers => drivers.driversAvailables.markers,
                me: state => state.auth.me
            }),

            drivers() {
                let client = {
                    position: this.latLngClient,
                    infoText: this.me.name
                }

                this.markers.push(client)
                return this.markers
            }
        },

        methods: {
            ...mapActions([
                'getDriversAvailable',
                'activeDriverService'
            ]),

            toggleInfoWindow: function (marker, idx) {
                this.infoWindowPos = marker.position
                this.infoContent = marker.infoText
                // Check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen
                }
                // If different marker set infowindow to open and reset current marker index
                else {
                    this.infoWinOpen = true;
                    this.currentMidx = idx;
                }
            },

            changePlace(place) {
                let lat = place.geometry.location.lat()
                let lng = place.geometry.location.lng()
                let location = {lat: lat, lng: lng}

                this.getDriversAvailable(location)
                this.latLngClient = location
            },

            onChangeStatusDriver(event) {
                let form = {
                    lat: this.latLngClient.lat,
                    lng: this.latLngClient.lng,
                    user_id: this.me.id,
                    active: event.value ? 1 : 0
                }

                if (event.value) {
                    this.listenForRequestServices()
                } else {
                    this.leaveForRequestServices()
                }

                this.activeDriverService(form)
            },

            listenForRequestServices() {
                Echo.channel('requestServices')
                    .listen('ServiceRequested', data => {
                        this.requestServices.push(data);

                        let name = data.name;

                        this.$notify({
                            type: 'info',
                            group: 'dashboard_request_service',
                            title: 'Servicio Solicitado',
                            text: `El cliente: ${name} ha realizado una solicitud de taxi.`,
                            duration: 10000
                        });
                    })
            },

            leaveForRequestServices() {
                Echo.leave('requestServices')
                this.leaveForRequestedServicesAcceptByClient()
            },

            listenForRequestedServicesAcceptByClient() {
                Echo.channel(`serviceAcceptedByClient.${this.me.id}`)
                    .listen('RequestedServicesAcceptedByClient', data => {
                        if (data.driver_id === this.me.id) {
                            this.messageServiceAcceptedByClient = data.message
                            Swal.fire({
                                title: 'Servicio Aceptado',
                                text: data.message,
                                type: 'info',
                                showCancelButton: false,
                                confirmButtonText: 'Aceptar',
                            })
                        }
                    })
            },

            leaveForRequestedServicesAcceptByClient() {
                Echo.leave(`serviceAcceptedByClient.${this.me.id}`)
            },

            acceptService(row) {
                this.servicesAccepted = true
                window.open(`/servicios/aceptar/${row.id}/${this.me.id}`, '_blank');
            },

            cancelService() {
                this.servicesAccepted = false
                this.leaveForRequestedServicesAcceptByClient()
            }
        },

        components: {
            BoxUser,
            HeaderForm,
            Spinner
        },

        async created() {
            if (this.me.type === 1) { // 1- Client
                try {
                    const {coords} = await this.getCurrentPositionUser()
                    if (coords.latitude && coords.longitude) {
                        this.latLngClient = {lat: coords.latitude, lng: coords.longitude}
                    }
                } catch (e) {

                }
            }
        },

        mounted() {
            if (this.me.type === 1) { // 1- Client
                this.getDriversAvailable()
            } else {
                this.stateDriver = this.me && this.me.transportation_available ? this.me.transportation_available.active : false
                if (this.me.transportation_available.active) {
                    this.listenForRequestServices();
                    this.listenForRequestedServicesAcceptByClient()
                }
            }
        }
    }
</script>
