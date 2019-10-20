<template>
    <box-user>
        <h3>Actualizar contraseña</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card app_card m-4">
                    <div class="card-header">Actualizar contraseña</div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="login_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="current" class="col control-label">
                                    Contraseña actual <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input id="current" type="password" name="current"
                                           v-validate="'required|min:6|max:20'"
                                           data-vv-as="Contraseña actual" class="form-control"
                                           v-model.trim="form.current"
                                           :class="{ 'is-invalid': submitted && (errors.has('current') || serverErrors.current) }">

                                    <div v-if="submitted && (errors.has('current') || serverErrors.current)"
                                         class="invalid-feedback">
                                        {{ errors.first('current') }}
                                        <template v-for="error in serverErrors.current">{{ error }}</template>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group">
                                <label for="password" class="col control-label">
                                    Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-model="form.password" v-validate="'required|min:6|max:20'"
                                           data-vv-as="Contraseña" id="password" type="password" name="password"
                                           class="form-control" ref="password"
                                           :class="{ 'is-invalid': submitted && (errors.has('password') || serverErrors.password) }">

                                    <div v-if="submitted && (errors.has('current') || serverErrors.current)"
                                         class="invalid-feedback">
                                        {{ errors.first('password') }}
                                        <template v-for="error in serverErrors.password">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="col control-label">
                                    Confirmar contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input v-model="form.password_confirmation"
                                           v-validate="'required|confirmed:password|min:6|max:20'"
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
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'administration' }" tag="button" type="button" class="btn btn-cancel mr-3">
                                        Cancelar
                                    </router-link>
                                    <button @keyup.enter="onSubmit" type="submit" class="btn btn-accept mr-2" :disabled="loading">
                                        Actualizar
                                    </button>
                                    <spinner v-if="loading" size="medium"></spinner>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <notifications group="reset_password" position="bottom right"/>
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
                    current: null,
                    password: null,
                    password_confirmation: null,
                },
                formDefault: {},
                submitted: false,
                loading: false,
                error: '',
                serverErrors: {},
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                phone_verify: state => state.auth.phone_verify,
            })
        },

        methods: {
            ...mapActions([
                'password_update',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.serverErrors = {}
                        this.loading = true
                        this.password_update(this.form)
                            .then(() => {
                                this.submitted = false
                                this.loading = false
                                this.form = this.formDefault
                                this.$notify({
                                    type: 'success',
                                    group: 'reset_password',
                                    title: 'Contraseña',
                                    text: 'La contraseña ha sido actualizada'
                                });
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'reset_password',
                                    title: 'Contraseña',
                                    text: 'Ha ocurrido un error al actualizar la contraseña'
                                });
                                this.error = data.message
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

        },

        mounted() {
            this.formDefault = this.form
        },

        components: {
            Spinner,
            BoxUser
        }
    }
</script>

<style scoped>

</style>