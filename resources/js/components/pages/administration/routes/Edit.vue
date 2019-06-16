<template>
    <box-user>
        <div class="row">
            <div class="col-12">
                <header-form>Servicio</header-form>
            </div>

            <div class="col-10">
                <spinner v-if="loadingToShow" size="medium"></spinner>
                <template v-else>
                    <div class="card app_card">
                        <div class="card-header">Actualizar Ruta</div>
                        <div class="card-body">
                            <form class="form-horizontal" id="new_route_form" role="form" autocomplete="off"
                                  @submit.prevent="onSubmit">

                                <div class="form-row form-group">
                                    <label for="name">
                                        Nombre de ruta
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input v-model="form.name" v-validate="'required|max:191'"
                                           data-vv-as="Nombre de ruta" id="name" name="name" type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': submitted && errors.has('name') }"/>
                                    <div v-if="submitted && errors.has('name')" class="invalid-feedback">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>

                                <div class="form-row form-group">
                                    <div class="form-group col-md-6">
                                        <label for="place_origen">Origen<span class="text-danger">*</span></label>
                                        <gmap-autocomplete ref="origin" class="form-control" id="place_origen"
                                                           :value="form.formatted_address_start"
                                                           name="place_origen" @place_changed="setPlaceOrigin">
                                        </gmap-autocomplete>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="place_destination">Destino<span class="text-danger">*</span></label>
                                        <gmap-autocomplete class="form-control" id="place_destination"
                                                           :value="form.formatted_address_end"
                                                           name="place_destination"
                                                           @place_changed="setPlaceDestination">
                                        </gmap-autocomplete>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col d-flex justify-content-start">
                                        <router-link :to="{ name: 'routes' }" tag="button" class="btn btn-cancel">
                                            Cancelar
                                        </router-link>
                                        <button type="submit" class="btn btn-accept ml-4" :disabled="loading">
                                            Actualizar
                                        </button>
                                        <spinner v-if="loading" size="medium" class="ml-2"></spinner>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <notifications group="edit_route"/>
                </template>
            </div>
        </div>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'
    import HeaderForm from '../layout/header_form'

    export default {
        name: "Edit",

        data() {
            return {
                form: {
                    id: null,
                    name: null,
                    lat_start: null,
                    lng_start: null,
                    formatted_address_start: null,
                    lat_end: null,
                    lng_end: null,
                    formatted_address_end: null,
                },
                submitted: false,
                loading: false,
                loadingToShow: true,
                placeOrigin: null,
                placeDestination: null
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                route: state => state.routes.route,
            }),
        },

        methods: {
            ...mapActions([
                'getRoute',
                'updateRoute',
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
                            this.form.formatted_address_start = this.placeOrigin.formatted_address
                        }

                        if (this.placeDestination) {
                            this.form.lat_end = this.placeDestination.geometry.location.lat()
                            this.form.lng_end = this.placeDestination.geometry.location.lng()
                            this.form.formatted_address_end = this.placeDestination.formatted_address
                        }

                        this.updateRoute(this.form)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'edit_route',
                                    title: 'Ruta',
                                    text: 'Se ha modificado la ruta correctamente'
                                });

                                this.$router.replace('/rutas')
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'edit_route',
                                    title: 'Ruta',
                                    text: 'Ha ocurrido un error al modificar la ruta.'
                                });
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

            setPlaceOrigin(place) {
                this.placeOrigin = place
                this.form.formatted_address_start = place.formatted_address
            },

            setPlaceDestination(place) {
                this.placeDestination = place
                this.form.formatted_address_end = place.formatted_address
            }
        },

        created() {
            this.getRoute(this.$route.params.id)
                .then(() => {
                    this.loadingToShow = false
                    this.form = this.route;
                })
                .catch((data) => {
                    this.loadingToShow = false
                    this.serverErrors = data.errors || {}
                })
        },

        components: {
            Spinner,
            BoxUser,
            HeaderForm
        }
    }
</script>

<style scoped>

</style>