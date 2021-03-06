<template>
    <div class="container">
        <h3>Recuperar contraseña</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-12 col-md-6">
                <div class="card app_card m-4">
                    <div class="card-header">Recuperar contraseña</div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Cerrar">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <form id="login_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="password" class="col control-label">
                                    Nueva Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input id="password" type="password" name="password"
                                           v-validate="'required|min:6|max:20'"
                                           data-vv-as="Contraseña" class="form-control"
                                           v-model.trim="form.password" ref="password"
                                           :class="{ 'is-invalid': submitted && (errors.has('password') || serverErrors.password) }">

                                    <div v-if="submitted && (errors.has('password') || serverErrors.password)"
                                         class="invalid-feedback">
                                        {{ errors.first('password') }}
                                        <template v-for="error in serverErrors.password">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation" class="col control-label">
                                    Confirmar Contraseña <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input id="password_confirmation" type="password" name="password_confirmation"
                                           v-validate="'required|confirmed:password|min:6|max:20'"
                                           data-vv-as="Confirmar Contraseña" class="form-control"
                                           v-model.trim="form.password_confirmation"
                                           :class="{ 'is-invalid': submitted && (errors.has('password_confirmation') || serverErrors.password_confirmation) }">

                                    <div v-if="submitted && (errors.has('password_confirmation') || serverErrors.password_confirmation)"
                                         class="invalid-feedback">
                                        {{ errors.first('password_confirmation') }}
                                        <template v-for="error in serverErrors.password_confirmation">{{ error }}
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-start">
                                    <button @keyup.enter="onSubmit" type="submit" class="btn btn-accept" :disabled="loading">
                                        Recuperar
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

    export default {

        data() {
            return {
                form: {
                    email: null,
                    password: null,
                    password_confirmation: null,
                    token: null
                },
                submitted: false,
                loading: false,
                disabledResetButton: false,
                error: null,
                serverErrors: {},
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
                find_token_data: state => state.auth.find_token_data,
                password_reset_data: state => state.auth.password_reset_data,
            })
        },

        methods: {

            ...mapActions([
                'find_token',
                'password_reset',
            ]),

            onSubmit() {
                if(!this.find_token_data){
                    this.error = 'Error inesperado al recuperar la contraseña, por favor vuelva a intentarlo.'

                    return
                }

                this.submitted = true;
                this.error = null
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.submitted = false
                        this.loading = true
                        this.form.token = this.find_token_data.token
                        this.form.email = this.find_token_data.email

                        this.password_reset(this.form)
                            .then(() => {
                                this.loading = false
                                if (this.me) {
                                    Swal.fire({
                                        text: 'Su contraseña ha sido restablecida',
                                        type: 'success',
                                        showCancelButton: false,
                                        confirmButtonText: 'Aceptar',
                                    }).then(() => {
                                        this.$router.replace('/')
                                    })

                                    return;
                                }

                                Swal.fire({
                                    text: 'La contraseña no ha sido ha sido restablecida, intentelo de nuevo',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Aceptar',
                                }).then(() => {
                                    this.$router.replace('/entrar')
                                })
                            })
                            .catch((data) => {
                                this.loading = false
                                this.submitted = false
                                this.error = data.message
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

        },
        mounted() {
            if (this.$route.params.token) {
                this.disabledResetButton = false;
                this.find_token(this.$route.params.token)
                    .then(() => {
                        this.disabledResetButton = true;
                        this.loading = false
                        this.form.token
                    })
                    .catch((data) => {
                        this.loading = false
                        this.error = data.message
                        this.serverErrors = data.errors || {}
                    })
            }
        },
        components: {
            Spinner
        }
    }
</script>

<style scoped>

</style>