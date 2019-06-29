<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card app_card m-4">
                    <div class="card-header">Registrarse</div>
                    <div class="card-body">
                        <form class="form-horizontal" id="register_form" role="form" autocomplete="off"
                              @submit.prevent="onSubmit" novalidate>

                            <div class="form-group">
                                <label for="name" class="col control-label">Nombre de Usuario <span class="text-danger">*</span></label>
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
                                <label for="password" class="col control-label">Contraseña <span
                                        class="text-danger">*</span></label>
                                <div class="col">
                                    <input v-model="form.password" v-validate="'required|min:6|max:18'"
                                           data-vv-as="Contraseña" id="password" type="password" name="password"
                                           class="form-control" ref="password"
                                           :class="{ 'is-invalid': submitted && errors.has('password') }">

                                    <div v-if="submitted && errors.has('password')" class="invalid-feedback">
                                        {{ errors.first('password') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="col control-label">
                                    Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-model="form.password_confirmation"
                                           v-validate="'required|confirmed:password|min:6|max:18'"
                                           data-vv-as="Confirmar Contraseña" id="password_confirmation"
                                           name="password_confirmation" type="password"
                                           class="form-control"
                                           :class="{ 'is-invalid': submitted && errors.has('password_confirmation') }"/>

                                    <div v-if="submitted && errors.has('password_confirmation')"
                                         class="invalid-feedback">
                                        {{ errors.first('password_confirmation') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="first_name" class="col control-label">
                                    Nombre o Razón Social <span class="text-danger">*</span>
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
                                           name="last_name" type="text" class="form-control" v-model="form.last_name"
                                           :class="{ 'is-invalid': submitted && errors.has('last_name') }">

                                    <div v-if="submitted && errors.has('last_name')"
                                         class="invalid-feedback">
                                        {{ errors.first('last_name') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="birth_date" class="col control-label">
                                    Fecha de Nacimiento <span class="text-danger">*</span>
                                </label>

                                <div class="col">
                                    <date-picker id="birth_date" name="birth_date" v-model="form.birth_date"
                                                 style="width: 300px; display: block;" value-type="date"
                                                 :lang="timePicker.lang" type="date" :format="timePicker.date" confirm
                                                 confirm-text="Confirmar" v-validate="'required'"
                                                 data-vv-as="Fecha de Nacimiento"
                                                 :input-class="[ 'form-control', submitted && (serverErrors.birth_date || errors.has('birth_date')) ? 'is-invalid': '']">
                                    </date-picker>

                                    <input type="text" class="form-control" v-show="false"
                                           :class="submitted && (serverErrors.birth_date || errors.has('birth_date')) ? 'is-invalid': ''"/>

                                    <div v-if="submitted && (serverErrors.birth_date || errors.has('birth_date'))"
                                         class="invalid-feedback">
                                        {{ errors.first('birth_date') }}
                                        <template v-for="error in serverErrors.birth_date">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone" class="col control-label">
                                    Teléfono <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <vue-phone-number-input v-validate="'required|max:191'" data-vv-as="Teléfono"
                                                            id="phone" name="phone" type="text"
                                                            v-model="form.phone" :translations="phone.translations"
                                                            :default-country-code="phone.countryCode"
                                                            :class="{ 'is-invalid': submitted && (errors.has('phone') || serverErrors.phone) }"/>

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
                                    <gmap-autocomplete class="form-control" id="direction" name="direction"
                                                       @place_changed="setDirection"
                                                       @keypress.enter="$event.preventDefault()"
                                                       :class="{ 'is-invalid': submitted && serverErrors.direction }">
                                    </gmap-autocomplete>

                                    <div v-if="submitted && (serverErrors.direction || serverErrors.direction)"
                                         class="invalid-feedback">
                                        <template v-for="error in serverErrors.direction">{{ error }}</template>
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
                                        <select v-validate="'required|max:191'"
                                                data-vv-as="Tipo de licencia de conducir"
                                                name="license_types_id" id="license_types_id" class="form-control"
                                                v-model.number="form.license_types_id"
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
                                            <input v-validate="'required|image'" data-vv-as="Foto"
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
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'required|image'" data-vv-as="Foto"
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
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'required|image'"
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
                                                {{ imageCertificateBackgroundLabel }}
                                            </label>
                                        </div>

                                        <div v-if="submitted && errors.has('image_certificate_background')"
                                             class="invalid-feedback">
                                            {{ errors.first('image_certificate_background') }}
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="form-group">
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'login' }" tag="button" class="btn btn-cancel">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-accept ml-4" :disabled="loading">
                                        Registrarse
                                    </button>
                                    <spinner v-if="loading" size="medium" class="ml-2"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import Swal from 'sweetalert2'
    import DatePicker from 'vue2-datepicker'

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
                    birth_date: null,
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
                direction: {},
                phone: {
                    countryCode: 'EC',
                    translations: {
                        countrySelectorLabel: 'Código del país',
                        countrySelectorError: 'Choisir un pays',
                        phoneNumberLabel: 'Número de teléfono',
                        example: 'Ejemplo :'
                    }
                },
                timePicker: {
                    lang: {
                        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                        placeholder: {
                            date: 'Seleccione el día'
                        }
                    },
                    date: 'DD/MM/YYYY'
                }
            }
        },

        computed: {
            ...mapState({
                lists: state => state.nomenclators.lists ? state.nomenclators.lists : {'licenseTypes': []},
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
                'register',
                'nomenclators',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        let formData, key;
                        this.form.birth_date = DatePicker.fecha.format(new Date(this.form.birth_date), 'DD/MM/YYYY')

                        if (!this.isClient()) {
                            formData = new FormData()
                            for (key in this.form) {
                                formData.append(key, this.form[key]);
                            }
                            formData.append('photo', this.selectedPhoto, this.selectedPhoto.name)
                            formData.append('image_driver_license', this.imageDriveLicense, this.imageDriveLicense.name)
                            formData.append('image_permit_circulation', this.imagePermitCirculation, this.imagePermitCirculation.name)
                            formData.append('image_certificate_background', this.imageCertificateBackground, this.imageCertificateBackground ? this.imageCertificateBackground.name : '')
                        }

                        this.serverErrors = {}
                        this.register(formData ? formData : this.form)
                            .then(() => {
                                this.loading = false

                                Swal.fire({
                                    text: 'Para la activación  de su cuenta se enviará un código al celular, por favor verifique los datos para registrarse.',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Aceptar',
                                }).then(() => {
                                    this.$router.replace('/entrar')
                                })
                            })
                            .catch((data) => {
                                this.loading = false
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

            setDirection(place) {
                let that = this
                this.form.city = null
                this.form.city = null

                for (let address  of place.address_components) {
                    Object.keys(address).forEach(function (prop) {
                        if (prop === 'types') {
                            address['types'].forEach(function (propType) {
                                if (propType === 'locality') {
                                    that.form.city = address.long_name
                                }

                                if (propType === 'postal_code') {
                                    that.form.postal_code = address.long_name
                                }
                            })
                        }
                    })
                }
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

        mounted() {
            this.form.type = this.$route.params.type === 'cliente' ? 1 : 2;
            this.nomenclators();
        },

        components: {
            DatePicker,
            Spinner
        }

    }
</script>

<style scoped>

</style>