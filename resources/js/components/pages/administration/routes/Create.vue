<template>
    <box-user>
        <div class="row">
            <div class="col-12">
                <header-form>Servicio</header-form>
            </div>

            <div class="col-10">
                <div class="card app_card">
                    <div class="card-header">Nueva Ruta</div>
                    <div class="card-body">
                        <form class="form-horizontal" id="new_route_form" role="form" autocomplete="off"
                              @submit.prevent="onSubmit">

                            <div class="form-row form-group">
                                <label for="name">
                                    Nombre de ruta
                                    <span class="text-danger">*</span>
                                </label>
                                <input v-model="form.name" id="name" name="name" type="text"
                                       class="form-control" :class="{ 'is-invalid': submitted && serverErrors.name }"/>
                                <div v-if="submitted && serverErrors.name"
                                     class="invalid-feedback">
                                    <template v-for="error in serverErrors.name">{{ error }}</template>
                                </div>
                            </div>

                            <div class="form-row form-group">
                                <div class="form-group col-md-6">
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
                                <div class="form-group col-md-6">
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
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'routes' }" tag="button" class="btn btn-cancel">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-accept ml-4" :disabled="loading">
                                        Adicionar
                                    </button>
                                    <spinner v-show="loading" size="medium" class="ml-2"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="create_route"/>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../../layout/BoxUser'
    import HeaderForm from '../layout/header_form'

    export default {
        name: "Create",

        data() {
            return {
                form: {
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
                placeOrigin: null,
                placeDestination: null,
                serverErrors: {}
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),
        },

        methods: {
            ...mapActions([
                'createRoute',
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

                        this.createRoute(this.form)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'create_route',
                                    title: 'Ruta',
                                    text: 'Se ha adicionado la ruta correctamente'
                                });

                                this.$router.replace('/rutas')
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'create_route',
                                    title: 'Ruta',
                                    text: 'Ha ocurrido un error al adicionar la ruta.'
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
            Spinner,
            BoxUser,
            HeaderForm
        }
    }
</script>

<style scoped>

</style>