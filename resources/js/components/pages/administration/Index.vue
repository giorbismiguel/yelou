<template>
    <box-user>
        <h3>Choferes disponibles</h3>
        <hr>
        <div class="row mb-2">
            <div class="col text-right">
                <router-link class="btn btn-success btn-sm --uppercase" :to="{name: 'services_create'}">
                    Solicitar Servicio
                </router-link>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <GmapMap
                        :center="{lat:-0.180653, lng:-78.467834}"
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
    </box-user>
</template>

<script>
    import BoxUser from '../../layout/BoxUser'
    import {mapState, mapActions} from 'vuex'

    export default {
        name: "Administration",

        data() {
            return {
                infoContent: '',
                infoWindowPos: null,
                infoWinOpen: false,
                currentMidx: null,
                //optional: offset infowindow so it visually sits nicely on top of our marker
                infoOptions: {
                    pixelOffset: {
                        width: 0,
                        height: -35
                    }
                },
            }
        },
        computed: {
            ...mapState({
                markers: drivers => drivers.driversAvailables.markers
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
            }
        },

        components: {
            BoxUser
        },

        created() {
            this.getDriversAvailable();
        }
    }
</script>

<style scoped>

</style>