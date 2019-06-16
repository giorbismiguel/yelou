<template>
    <div class="container">
        <h3>Activar Cuenta</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card m-4">
                    <div class="card-header">Activar Cuenta</div>
                    <div class="card-body">

                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="code_activation_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="code_activation" class="col control-label">
                                    Ingrese el código de activación
                                </label>
                                <div class="col">
                                    <input id="code_activation" type="text" name="code_activation"
                                           v-validate="'required|length:6'" data-vv-as="Código de activación"
                                           class="form-control" v-model.trim="form.code_activation"
                                           :class="{ 'is-invalid': submitted && (errors.has('code_activation') || serverErrors.code_activation) }">

                                    <div v-if="submitted && (errors.has('code_activation') || serverErrors.code_activation)"
                                         class="invalid-feedback">
                                        {{ errors.first('code_activation') }}
                                        <template v-for="error in serverErrors.code_activation">{{ error }}</template>
                                    </div>
                                </div>
                                <router-link class="nav-link" :to="{ name: 'new_code' }">
                                    Solicitar Nuevo Código
                                </router-link>
                            </div>

                            <div class="form-group mt-5">
                                <div class="col d-flex justify-content-end">
                                    <router-link :to="{ name: 'home' }" tag="button" class="btn btn-light">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-primary ml-4" :disabled="loading">
                                        Activar
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

    export default {

        data() {
            return {
                form: {
                    code_activation: '',
                    phone: null,
                },
                submitted: false,
                submittedCode: false,
                loading: false,
                loadingCode: false,
                error: '',
                serverErrors: {},
            }
        },

        computed: {
            ...mapState({
                phone: state => state.auth.phone,
                phone_has_been_actived: state => state.auth.phone_has_been_actived,
            })
        },

        methods: {

            ...mapActions([
                'active_account',
                'new_activation_code',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        this.form.phone = this.phone
                        this.loading = true
                        this.active_account(this.form)
                            .then(() => {
                                this.loading = false
                                if (this.phone_has_been_actived) {
                                    this.$router.replace('/entrar')
                                }
                            })
                            .catch((data) => {
                                this.loading = false
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