<template>
    <box-user>
        <h3>Perfil</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card m-4">
                    <div class="card-header">Edite el Perfil</div>
                    <div class="card-body">
                        <form class="form-horizontal" id="register_form" role="form" autocomplete="off"
                              @submit.prevent="onSubmit">

                            <div class="form-group">
                                <label for="name" class="col control-label">Nombre de usuario <span
                                        class="text-danger">*</span></label>
                                <div class="col">
                                    <input v-model="form.name" v-validate="'required|max:191'"
                                           data-vv-as="Nombre de usuario" id="name" name="name" type="text"
                                           class="form-control"
                                           :class="{ 'is-invalid': submitted && errors.has('name') }"/>
                                    <div v-if="submitted && errors.has('name')" class="invalid-feedback">
                                        {{ errors.first('name') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col control-label">Correo electrónico <span
                                        class="text-danger">*</span></label>
                                <div class="col">
                                    <input v-model="form.email" v-validate="'required|email|max:191'"
                                           data-vv-as="Correo electrónico" id="email" name="email" type="email"
                                           class="form-control"
                                           :class="{ 'is-invalid': submitted && (errors.has('email') || serverErrors.email) }"/>

                                    <div v-if="submitted && (errors.has('email') || serverErrors.email)"
                                         class="invalid-feedback">
                                        {{ errors.first('email') }}
                                        <template v-for="error in serverErrors.email">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col control-label">
                                    Nombre o razon social <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-validate="'required|max:191'" data-vv-as="Nombre o razon social"
                                           id="first_name" name="first_name" type="text" class="form-control"
                                           v-model="form.first_name"
                                           :class="{ 'is-invalid': submitted && errors.has('first_name') }"/>

                                    <div v-if="submitted && errors.has('first_name')"
                                         class="invalid-feedback">
                                        {{ errors.first('first_name') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="last_name" class="col control-label">
                                    Apellido(s) <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-validate="'required|max:191'" data-vv-as="Apellido (s)" id="last_name"
                                           name="last_name" type="text" class="form-control"
                                           v-model="form.last_name"
                                           :class="{ 'is-invalid': submitted && errors.has('last_name') }">

                                    <div v-if="submitted && errors.has('last_name')"
                                         class="invalid-feedback">
                                        {{ errors.first('last_name') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col control-label">
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-validate="'required|max:191'" data-vv-as="Teléfono" id="phone"
                                           name="phone" type="text" class="form-control" v-model="form.phone"
                                           :class="{ 'is-invalid': submitted && (errors.has('phone') || serverErrors.phone) }">

                                    <div v-if="submitted && (errors.has('phone') || serverErrors.phone) "
                                         class="invalid-feedback">
                                        {{ errors.first('phone') }}
                                        <template v-for="error in serverErrors.phone">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ruc" class="col control-label">
                                    RUC <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-validate="'required|max:191'" data-vv-as="RUC" id="ruc"
                                           name="ruc" type="text" class="form-control" v-model="form.ruc"
                                           :class="{ 'is-invalid': submitted && errors.has('ruc') }">

                                    <div v-if="submitted && errors.has('ruc')"
                                         class="invalid-feedback">
                                        {{ errors.first('ruc') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="direction" class="col control-label">Dirección</label>
                                <div class="col">
                                    <input v-validate="'max:191'" data-vv-as="Dirección" id="direction"
                                           name="direction" type="text" class="form-control"
                                           v-model="form.direction"
                                           :class="{ 'is-invalid': submitted && errors.has('direction') }">

                                    <div v-if="submitted && errors.has('direction')"
                                         class="invalid-feedback">
                                        {{ errors.first('direction') }}
                                    </div>
                                </div>
                            </div>

                            <template v-if="form.type === 1">
                                <div class="form-group">
                                    <label for="city" class="col control-label">
                                        Ciudad <span class="text-danger">*</span>
                                    </label>
                                    <div class="col">
                                        <input v-validate="'required|max:191'" data-vv-as="Ciudad" id="city"
                                               name="city" type="text" class="form-control" v-model="form.city"
                                               :class="{ 'is-invalid': submitted && errors.has('city') }">

                                        <div v-if="submitted && errors.has('city')"
                                             class="invalid-feedback">
                                            {{ errors.first('city') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="postal_code" class="col control-label">Código Postal</label>
                                    <div class="col">
                                        <input v-validate="'max:191'" data-vv-as="Código Postal"
                                               id="postal_code" name="postal_code" type="text" class="form-control"
                                               v-model="form.postal_code"
                                               :class="{ 'is-invalid': submitted && errors.has('postal_code') }">

                                        <div v-if="submitted && errors.has('postal_code')"
                                             class="invalid-feedback">
                                            {{ errors.first('postal_code') }}
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <template v-else>
                                <div class="form-group">
                                    <label for="license_types_id" class="col control-label">
                                        Tipo de licencia de conducir <span class="text-danger">*</span>
                                    </label>
                                    <div class="col">
                                        <select v-validate="'max:191'"
                                                data-vv-as="Tipo de licencia de conducir"
                                                name="license_types_id" id="license_types_id" class="form-control"
                                                v-model="form.license_types_id"
                                                :class="{ 'is-invalid': submitted && errors.has('license_types_id') }">
                                            <option v-for="(item, key, index) in lists.licenseTypes" :value="key">
                                                {{ item }}
                                            </option>
                                        </select>

                                        <div v-if="submitted && errors.has('license_types_id')"
                                             class="invalid-feedback">
                                            {{ errors.first('license_types_id') }}
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" data-vv-as="Foto"
                                                   @change="onPhotoSelected" ref="Photo" type="file" name="photo"
                                                   id="photo" class="custom-file-input"
                                                   :class="{ 'is-invalid': submitted && errors.has('photo') }"/>
                                            <label for="photo" class="custom-file-label">
                                                {{ photoLabel }} <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && errors.has('photo')"
                                             class="invalid-feedback">
                                            {{ errors.first('photo') }}
                                        </div>
                                    </div>
                                    <img :src="'/storage/' + form.photo" class="rounded float-right mt-2 mb-2" alt="Foto" style="width: 200px; height: 200px;"/>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" data-vv-as="Imagen de la licencia de conducir"
                                                   @change="onImageDriveLicenseSelected" ref="ImageDriveLicense"
                                                   type="file" name="image_driver_license"
                                                   id="image_driver_license" class="custom-file-input"
                                                   :class="{ 'is-invalid': submitted && errors.has('image_driver_license') }"/>
                                            <label for="image_driver_license" class="custom-file-label">
                                                {{ imageDriveLicenseLabel }} <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && errors.has('image_driver_license')"
                                             class="invalid-feedback">
                                            {{ errors.first('image_driver_license') }}
                                        </div>
                                    </div>
                                    <img :src="'/storage/' + form.image_driver_license" class="rounded float-right mt-2 mb-2" alt="Foto" style="width: 200px; height: 200px;"/>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'"
                                                   data-vv-as="Imagen del Permiso de circulación"
                                                   @change="onImagePermitCirculationSelected"
                                                   ref="ImagePermitCirculation" type="file"
                                                   name="image_permit_circulation"
                                                   id="image_permit_circulation" class="custom-file-input"
                                                   :class="{ 'is-invalid': submitted && errors.has('image_permit_circulation') }"/>
                                            <label for="image_permit_circulation" class="custom-file-label">
                                                {{ imagePermitCirculationLabel }} <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && errors.has('image_permit_circulation')"
                                             class="invalid-feedback">
                                            {{ errors.first('image_permit_circulation') }}
                                        </div>
                                    </div>
                                    <img :src="'/storage/' + form.image_permit_circulation" class="rounded float-right mt-2 mb-2" alt="Foto" style="width: 200px; height: 200px;"/>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'"
                                                   data-vv-as="Imagen de Certificado de Antecedentes"
                                                   @change="onImageCertificateBackgroundSelected"
                                                   ref="ImageCertificateBackground" type="file"
                                                   name="image_certificate_background"
                                                   id="image_certificate_background" class="custom-file-input"
                                                   :class="{ 'is-invalid': submitted && errors.has('image_certificate_background') }"/>
                                            <label for="image_certificate_background" class="custom-file-label">
                                                {{ imageCertificateBackgroundLabel }} <span
                                                    class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && errors.has('image_certificate_background')"
                                             class="invalid-feedback">
                                            {{ errors.first('image_certificate_background') }}
                                        </div>
                                    </div>
                                    <img :src="'/storage/' + form.image_certificate_background" class="rounded float-right mt-2 mb-2" alt="Foto" style="width: 200px; height: 200px;"/>
                                </div>
                            </template>

                            <div class="form-group">
                                <div class="col d-flex justify-content-end">
                                    <router-link :to="{ name: 'home' }" tag="button" class="btn btn-light">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-primary ml-4" :disabled="loading">
                                        Actualizar
                                    </button>
                                    <spinner v-show="loading" size="medium" class="ml-2"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="update_profile"/>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../layout/BoxUser'

    export default {

        data() {
            return {
                form: {
                    type: 1,
                    name: null,
                    email: null,
                    password: null,
                    password_confirmation: null,
                    first_name: null,
                    last_name: null,
                    phone: null,
                    ruc: null,
                    direction: null,
                    city: null,
                    postal_code: null,
                    license_types_id: null,
                    photo: null,
                    image_driver_license: null,
                    image_permit_circulation: null,
                    image_certificate_background: null,
                },
                selectedPhoto: null,
                imageDriveLicense: null,
                imagePermitCirculation: null,
                imageCertificateBackground: null,
                serverErrors: {},
                submitted: false,
                loading: false,
            }
        },

        computed: {
            ...mapState({
                lists: state => state.nomenclators.lists ? state.nomenclators.lists : {'licenseTypes': []},
                me: state => state.auth.me,
            }),

            photoLabel() {
                return this.selectedPhoto ? this.selectedPhoto.name : 'Foto'
            },

            imageDriveLicenseLabel() {
                return this.imageDriveLicense ? this.imageDriveLicense.name : 'Imagen de la licencia de conducir'
            },

            imagePermitCirculationLabel() {
                return this.imagePermitCirculation ? this.imagePermitCirculation.name : 'Imagen del Permiso de circulación'
            },

            imageCertificateBackgroundLabel() {
                return this.imageCertificateBackground ? this.imageCertificateBackground.name : 'Imagen de Certificado de Antecedentes'
            },
        },

        methods: {

            ...mapActions([
                'updateProfile',
                'nomenclators',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true

                        let formData = new FormData(), key
                        for (key in this.form) {
                            formData.append(key, this.form[key]);
                        }

                        if (!this.isClient()) {
                            formData.append('photo', this.selectedPhoto, this.selectedPhoto ? this.selectedPhoto.name : null)
                            formData.append('image_driver_license', this.imageDriveLicense, this.imageDriveLicense ? this.imageDriveLicense.name : null)
                            formData.append('image_permit_circulation', this.imagePermitCirculation, this.imagePermitCirculation ? this.imagePermitCirculation.name : null)
                            formData.append('image_certificate_background', this.imageCertificateBackground, this.imageCertificateBackground ? this.imageCertificateBackground.name : null)
                        } else {
                            formData.delete('license_types_id')
                            formData.delete('photo')
                            formData.delete('image_driver_license')
                            formData.delete('image_permit_circulation')
                            formData.delete('image_certificate_background')
                        }

                        this.serverErrors = {}
                        this.updateProfile(formData)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'update_profile',
                                    title: 'Perfil',
                                    text: 'Su perfil ha sido actualizado'
                                });
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'update_profile',
                                    title: 'Perfil',
                                    text: 'Ha ocurrido un error al actualizar el perfil'
                                });
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

            onPhotoSelected(event) {
                this.selectedPhoto = event.target.files[0];
            },

            onImageDriveLicenseSelected(event) {
                this.imageDriveLicense = event.target.files[0];
            },

            onImagePermitCirculationSelected(event) {
                this.imagePermitCirculation = event.target.files[0];
            },

            onImageCertificateBackgroundSelected(event) {
                this.imageCertificateBackground = event.target.files[0];
            },

            isClient() {
                return this.form.type === 1;
            }
        },

        created() {
            this.form = this.me;
        },

        mounted() {
            this.nomenclators();
        },

        components: {
            Spinner,
            BoxUser
        }

    }
</script>
