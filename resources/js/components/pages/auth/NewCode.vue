<template>
    <div class="container">
        <h3>Obtener Nuevo código</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card m-4">
                    <div class="card-header">Obtener Nuevo código</div>
                    <div class="card-body">

                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="code_activation_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="phone" class="col control-label">
                                    Ingrese el Teléfono
                                </label>
                                <div class="col">
                                    <input id="phone" type="text" name="phone"
                                           v-validate="'required'" data-vv-as="Teléfono"
                                           class="form-control" v-model.trim="form.phone"
                                           :class="{ 'is-invalid': submitted && (errors.has('phone') || serverErrors.phone) }">

                                    <div v-if="submitted && (errors.has('phone') || serverErrors.phone)"
                                         class="invalid-feedback">
                                        {{ errors.first('phone') }}
                                        <template v-for="error in serverErrors.phone">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-2">
                                <div class="col text-center">
                                    <router-link :to="{ name: 'home' }" tag="button" class="btn btn-light">
                                        Cancelar
                                    </router-link>
                                    <button type="submit" class="btn btn-primary" :disabled="loading">
                                        Solicitar Nuevo código
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

    export default {

        data() {
            return {
                form: {
                    phone: null
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
                me: state => state.auth.me,
                code_activation: state => state.auth.code_activation,
            })
        },

        methods: {

            ...mapActions([
                'new_activation_code',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.loading = true
                        this.new_activation_code(this.form)
                            .then(() => {
                                this.loading = false
                                if (this.code_activation) {
                                    this.$router.replace('/verificar/codigo')
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