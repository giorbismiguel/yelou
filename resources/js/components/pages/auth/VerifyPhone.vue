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
                                <label for="code_activation" class="col control-label">Código de activación</label>
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
                            </div>

                            <div class="form-group mb-2">
                                <div class="col text-center">
                                    <button @click="getNewActivationCode" type="submit" class="btn btn-light">
                                        Solicitar Nuevo código
                                    </button>
                                </div>
                            </div>

                            <div class="form-group mt-5">
                                <div class="col d-flex justify-content-end">
                                    <button @click="getNewActivationCode" type="submit" class="btn btn-light">
                                        Cancelar
                                    </button>
                                    <button type="submit" class="btn btn-primary ml-3">Activar</button>
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

    export default {

        data() {
            return {
                form: {
                    code_activation: '',
                },
                submitted: false,
                error: '',
                serverErrors: {},
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
        },

        methods: {

            ...mapActions([
                'active_account',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.active_account(this.form)
                            .then(() => {
                                if (this.active) {
                                    this.$router.replace('/login')
                                }
                            })
                            .catch((data) => {
                                this.error = data.message
                            })
                    }
                });
            },

            getNewActivationCode() {

            }
        }
    }
</script>

<style scoped>

</style>