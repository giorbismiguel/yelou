<template>
    <box-user>
        <h3>Ruta</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card m-4">
                    <div class="card-header">Nueva Ruta</div>
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
                                    <gmap-autocomplete class="form-control" id="place_origen" name="place_origen"
                                                       @place_changed="setPlaceOrigin">
                                    </gmap-autocomplete>
                                    <div v-if="submitted && errors.has('name')" class="invalid-feedback">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="place_destination">Destino<span class="text-danger">*</span></label>
                                    <gmap-autocomplete class="form-control" id="place_destination"
                                                       name="place_destination" @place_changed="setPlaceDestination">
                                    </gmap-autocomplete>
                                    <div v-if="submitted && errors.has('name')" class="invalid-feedback">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-end">
                                    <router-link :to="{ name: 'administration' }" tag="button" class="btn btn-light">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-primary ml-4" :disabled="loading">
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
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),
        },

        methods: {
            ...mapActions([
                'create',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        this.serverErrors = {}
                        this.form.lat_start = this.placeOrigin.geometry.location.lat()
                        this.form.lng_start = this.placeOrigin.geometry.location.lng()
                        this.form.formatted_address_start = this.placeOrigin.formatted_address

                        this.form.lat_end = this.placeDestination.geometry.location.lat()
                        form.lng_end = this.placeDestination.geometry.location.lng()
                        form.formatted_address_end = this.placeOrigin.formatted_address

                        this.create(this.form)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'create_route',
                                    title: 'Ruta',
                                    text: 'Se ha adicionado la ruta correctamente'
                                });
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
            BoxUser
        }
    }
</script>

<style scoped>

</style>