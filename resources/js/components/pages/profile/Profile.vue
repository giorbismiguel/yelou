<template>
    <box-user>
        <h3>Perfil</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-8">
                <div class="card app_card m-4">
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
                                <label for="birth_date" class="col control-label">
                                    Fecha de Nacimiento <span class="text-danger">*</span>
                                </label>

                                <div class="col">
                                    <date-picker id="birth_date" name="birth_date" v-model="birth_date"
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
                                           :class="{ 'is-invalid': submitted && (serverErrors.ruc || errors.has('ruc')) }">

                                    <div v-if="submitted && (errors.has('ruc') || serverErrors.ruc)"
                                         class="invalid-feedback">
                                        {{ errors.first('ruc') }}
                                        <template v-for="error in serverErrors.ruc">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="direction" class="col control-label">Dirección</label>
                                <div class="col">
                                    <gmap-autocomplete class="form-control" id="direction" name="direction"
                                                       @place_changed="setDirection" :value="form.direction"
                                                       @keypress.enter="$event.preventDefault()"
                                                       placeholder="Escriba su dirección"
                                                       :class="{ 'is-invalid': submitted && serverErrors.direction }">
                                    </gmap-autocomplete>

                                    <div v-if="submitted && (errors.has('direction') || serverErrors.direction)"
                                         class="invalid-feedback">
                                        {{ errors.first('direction') }}
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

                                        <div v-if="submitted && (errors.has('city') || serverErrors.city)"
                                             class="invalid-feedback">
                                            {{ errors.first('city') }}
                                            <template v-for="error in serverErrors.city">{{ error }}</template>
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

                                        <div v-if="submitted && (errors.has('postal_code') || serverErrors.postal_code)"
                                             class="invalid-feedback">
                                            {{ errors.first('postal_code') }}
                                            <template v-for="error in serverErrors.postal_code">{{ error }}</template>
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

                                        <div v-if="submitted && (errors.has('license_types_id') || serverErrors.license_types_id)"
                                             class="invalid-feedback">
                                            {{ errors.first('license_types_id') }}
                                            <template v-for="error in serverErrors.license_types_id">
                                                {{ error }}
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" data-vv-as="Foto"
                                                   @change="onPhotoSelected" ref="Photo" type="file" name="photo"
                                                   id="photo" class="custom-file-input" accept="image/*"
                                                   :class="{ 'is-invalid': submitted && errors.has('photo') }"/>
                                            <label for="photo" class="custom-file-label">
                                                {{ photoLabel }} <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && (errors.has('photo') || serverErrors.photo)"
                                             class="invalid-feedback">
                                            {{ errors.first('photo') }}
                                            <template v-for="error in serverErrors.photo">
                                                {{ error }}
                                            </template>
                                        </div>

                                        <img :src="(form.photo ? '/storage/' + form.photo : '/img/default-image.png')"
                                             class="rounded float-right mt-2 mb-2"
                                             alt="Foto" style="width: 200px; height: 200px;"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" data-vv-as="Imagen de la licencia de conducir"
                                                   @change="onImageDriveLicenseSelected" ref="ImageDriveLicense"
                                                   type="file" name="image_driver_license" accept="image/*"
                                                   id="image_driver_license" class="custom-file-input"
                                                   :class="{ 'is-invalid': submitted && errors.has('image_driver_license') }"/>
                                            <label for="image_driver_license" class="custom-file-label">
                                                {{ imageDriveLicenseLabel }} <span class="text-danger">*</span>
                                            </label>
                                        </div>

                                        <div v-if="submitted && (errors.has('image_driver_license') || serverErrors.image_driver_license)"
                                             class="invalid-feedback">
                                            {{ errors.first('image_driver_license') }}
                                            <template v-for="error in serverErrors.image_driver_license">
                                                {{ error }}
                                            </template>
                                        </div>

                                        <img :src="form.image_driver_license ? '/storage/' + form.image_driver_license : '/img/default-image.png'"
                                             class="rounded float-right mt-2 mb-2" alt="Foto"
                                             style="width: 200px; height: 200px;"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" accept="image/*"
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

                                        <div v-if="submitted && (errors.has('image_permit_circulation') || serverErrors.image_permit_circulation)"
                                             class="invalid-feedback">
                                            {{ errors.first('image_permit_circulation') }}
                                            <template v-for="error in serverErrors.image_permit_circulation">
                                                {{ error }}
                                            </template>
                                        </div>

                                        <img :src="form.image_permit_circulation ? '/storage/' + form.image_permit_circulation : '/img/default-image.png'"
                                             class="rounded float-right mt-2 mb-2" alt="Foto"
                                             style="width: 200px; height: 200px;"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col">
                                        <div class="custom-file">
                                            <input v-validate="'image'" accept="image/*"
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

                                        <div v-if="submitted && (errors.has('image_certificate_background') || serverErrors.image_certificate_background)"
                                             class="invalid-feedback">
                                            {{ errors.first('image_certificate_background') }}
                                            <template v-for="error in serverErrors.image_certificate_background">
                                                {{ error }}
                                            </template>
                                        </div>

                                        <img :src="form.image_certificate_background ? '/storage/' + form.image_certificate_background : '/img/default-image.png'"
                                             class="rounded float-right mt-2 mb-2" alt="Foto"
                                             style="width: 200px; height: 200px;"/>
                                    </div>
                                </div>
                            </template>

                            <div class="form-group">
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'administration' }" tag="button" type="button"
                                                 class="btn btn-cancel">
                                        Cancelar
                                    </router-link>
                                    <button @keyup.enter="onSubmit" type="submit" class="btn btn-accept ml-4"
                                            :disabled="loading">
                                        Actualizar
                                    </button>
                                    <spinner v-if="loading" size="medium" class="ml-2"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="update_profile" position="bottom right"/>
    </box-user>
</template>

<script>
    import {mapState, mapActions} from 'vuex'
    import Spinner from 'vue-simple-spinner'
    import BoxUser from '../../layout/BoxUser'
    import DatePicker from 'vue2-datepicker'

    export default {
        name: "Profile",

        data() {
            return {
                form: {
                    type: 1,
                    name: null,
                    email: null,
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
                birth_date: null,
                timePicker: {
                    lang: {
                        days: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
                        months: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'],
                        placeholder: {
                            date: 'Seleccione el día'
                        }
                    },
                    date: 'DD/MM/YYYY'
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
                        this.form.birth_date = DatePicker.fecha.format(new Date(this.birth_date), 'DD/MM/YYYY')

                        for (key in this.form) {
                            formData.append(key, this.form[key])
                        }

                        if (!this.isClient()) {
                            if (this.selectedPhoto) {
                                formData.append('photo', this.selectedPhoto, this.selectedPhoto.name)
                            } else {
                                formData.delete('photo');
                            }

                            if (this.imageDriveLicense) {
                                formData.append('image_driver_license', this.imageDriveLicense, this.imageDriveLicense.name)
                            } else {
                                formData.delete('image_driver_license');
                            }

                            if (this.imagePermitCirculation) {
                                formData.append('image_permit_circulation', this.imagePermitCirculation, this.imagePermitCirculation.name)
                            } else {
                                formData.delete('image_permit_circulation');
                            }

                            if (this.imageCertificateBackground) {
                                formData.append('image_certificate_background', this.imageCertificateBackground, this.imageCertificateBackground.name)
                            } else {
                                formData.delete('image_certificate_background');
                            }
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
            },

            setDirection(place) {
                let that = this
                this.form.city = null
                this.form.postal_code = null
                this.form.direction = place.formatted_address

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
            }
        },

        created() {
            // let propertiesForm = [
            //     'type',
            //     'name',
            //     'email',
            //     'first_name',
            //     'last_name',
            //     'birth_date',
            //     'phone',
            //     'ruc',
            //     'direction',
            //     'city',
            //     'postal_code',
            //     'license_types_id',
            //     'photo',
            //     'image_driver_license',
            //     'image_permit_circulation',
            //     'image_certificate_background',
            // ]

            this.form = this.me
            this.birth_date = this.me.birth_date
        },

        mounted() {
            this.nomenclators();
        },

        components: {
            Spinner,
            BoxUser,
            DatePicker
        }

    }
</script>
