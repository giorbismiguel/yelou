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
        </template>

    </box-user>
</template>

<script>
    import BoxUser from '../../layout/BoxUser'
    import {mapState, mapActions} from 'vuex'
    import HeaderForm from './layout/header_form'
    import navigator from '../../../mixins/navigator'

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
                latLngClient: {lat: -0.180653, lng: -78.467834},
                stateDriver: false,
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
                this.infoWindowPos = marker.position;
                this.infoContent = marker.infoText;
                // Check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen;
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

                this.getDriversAvailable(location);
                this.latLngClient = location
            },

            onChangeStatusDriver(event) {
                let form = {
                    lat: this.latLngClient.lat,
                    lng: this.latLngClient.lng,
                    user_id: this.me.id,
                    active: event.value ? 1 : 0
                }

                this.activeDriverService(form)
            },

            listenForRequestServices() {
                Echo.channel('requestServices')
                    .listen('ServiceRequested', post => {
                        console.log(post);

                        if (!('Notification' in window)) {
                            alert('Web Notification is not supported');
                            return;
                        }

                        Notification.requestPermission(permission => {
                            let notification = new Notification('New post alert!', {
                                body: post.title, // content for the alert
                                icon: "https://pusher.com/static_logos/320x320.png" // optional image url
                            });

                            // link to page on clicking the notification
                            notification.onclick = () => {
                                window.open(window.location.href);
                            };
                        });
                    })
            }
        },

        components: {
            BoxUser,
            HeaderForm
        },

        async created() {
            if (this.me.type === 1) {
                try {
                    const {coords} = await this.getCurrentPositionUser()
                    if (coords.latitude && coords.longitude) {
                        this.latLngClient = {lat: coords.latitude, lng: coords.longitude}
                    }
                } catch (e) {

                }
            } else {
                this.listenForRequestServices();
            }
        },

        mounted() {
            if (this.me.type === 1) {
                this.getDriversAvailable();
            } else {
                this.stateDriver = this.me && this.me.transportation_available ? this.me.transportation_available.active : false
            }
        }
    }
</script>
