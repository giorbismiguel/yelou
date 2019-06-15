<template>
    <box-user>
        <template v-if="me.type === 1">
            <div class="col-12">
                <header-form>Choferes disponibles</header-form>
            </div>

            <div class="row mb-2">
                <div class="col-6">
                    <gmap-autocomplete class="form-control" @place_changed="changePlace"
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
                            :center="centerClient"
                            :zoom="15"
                            map-type-id="terrain"
                            style="width: 100%; min-height:80vh;"
                    >
                        <GmapInfoWindow
                                :options="infoOptions"
                                :position="infoWindowPos"
                                :opened="infoWinOpen"
                                @closeclick="infoWinOpen=false">
                            {{infoContent}}
                        </GmapInfoWindow>

                        <GmapMarker
                                :key="index"
                                v-for="(m, index) in markers"
                                :position="m.position"
                                :clickable="true"
                                @click="toggleInfoWindow(m,i)"
                        />
                    </GmapMap>
                </div>
            </div>
        </template>

        <template v-else>
            <h3>Administración</h3>
            <hr>
        </template>

    </box-user>
</template>

<script>
    import BoxUser from '../../layout/BoxUser'
    import {mapState, mapActions} from 'vuex'
    import HeaderForm from './layout/header_form'

    export default {
        name: "Administration",

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
                centerClient: {lat: -0.180653, lng: -78.467834}
            }
        },

        computed: {
            ...mapState({
                markers: drivers => drivers.driversAvailables.markers,
                me: state => state.auth.me,
            })
        },

        methods: {
            ...mapActions([
                'getDriversAvailable',
            ]),

            toggleInfoWindow: function (marker, idx) {
                this.infoWindowPos = marker.position;
                this.infoContent = marker.infoText;
                //check if its the same marker that was selected if yes toggle
                if (this.currentMidx == idx) {
                    this.infoWinOpen = !this.infoWinOpen;
                }
                //if different marker set infowindow to open and reset current marker index
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
                this.centerClient = location
            },

            getCurrentPositionUser() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(position => {
                        console.table(position)
                        this.centerClient = {lat: position.coords.latitude, lng: position.coords.longitude}
                    });
                }
            }
        },

        components: {
            BoxUser,
            HeaderForm
        },

        created() {
            this.getDriversAvailable();
        },

        mounted() {
            this.getCurrentPositionUser()
        }
    }
</script>

<style scoped>

</style>