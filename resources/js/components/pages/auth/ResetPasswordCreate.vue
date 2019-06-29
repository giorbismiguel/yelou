<template>
    <div class="container">
        <h3>Restablecer contraseña</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card app_card m-4">
                    <div class="card-header">Restablecer contraseña</div>
                    <div class="card-body">

                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="code_activation_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="email" class="col control-label">
                                    Ingrese el Correo electrónico <span class="text-danger">*</span>
                                </label>
                                <div class="col">
                                    <input id="email" type="text" name="email"
                                           v-validate="'required|email'" data-vv-as="Correo electrónico"
                                           class="form-control" v-model.trim="form.email"
                                           :class="{ 'is-invalid': submitted && (errors.has('email') || serverErrors.email) }">

                                    <div v-if="submitted && (errors.has('email') || serverErrors.email)"
                                         class="invalid-feedback">
                                        {{ errors.first('email') }}
                                        <template v-for="error in serverErrors.email">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <div class="col d-flex justify-content-start">
                                    <router-link :to="{ name: 'administration' }" tag="button" class="btn btn-cancel mr-3">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-accept mr-2" :disabled="loading">
                                        Restablecer
                                    </button>
                                    <spinner v-show="loadingCode" size="medium"></spinner>
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
                    email: null
                },
                submitted: false,
                submittedCode: false,
                loading: false,
                loadingCode: false,
                error: '',
                serverErrors: {},
            }
        },

        methods: {
            ...mapActions([
                'send_email',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        this.loadingCode = true
                        this.send_email(this.form)
                            .then(() => {
                                this.loadingCode = false
                                this.loading = false
                                Swal.fire({
                                    text: 'Se ha enviado un correo electrónico para restablecer la contraseña.',
                                    type: 'success',
                                    showCancelButton: false,
                                    confirmButtonText: 'Aceptar',
                                })
                            })
                            .catch((data) => {
                                this.loading = false
                                this.loadingCode = false
                                this.error = data.message
                            })
                    }
                });
            }
        },

        components: {
            Spinner
        }
    }
</script>

<style scoped>

</style>