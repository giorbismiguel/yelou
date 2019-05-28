<template>
    <div class="container">
        <h3>Autenticarse</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-6">
                <div class="card m-4">
                    <div class="card-header">Autenticarse</div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="login_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="email" class="col control-label">Correo electronico o Teléfono</label>
                                <div class="col">
                                    <input id="email" type="email" name="email" v-validate="'required|email|max:191'"
                                           data-vv-as="Correo electronico o Teléfono" class="form-control"
                                           v-model.trim="form.email"
                                           :class="{ 'is-invalid': submitted && (errors.has('email') || serverErrors.email) }">

                                    <div v-if="submitted && (errors.has('email') || serverErrors.email)"
                                         class="invalid-feedback">
                                        {{ errors.first('email') }}
                                        <template v-for="error in serverErrors.email">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="col control-label">Contraseña</label>
                                <div class="col">
                                    <input id="password" type="password" name="password"
                                           v-validate="'required|min:6|max:20'"
                                           data-vv-as="Contraseña" class="form-control" v-model.trim="form.password"
                                           :class="{ 'is-invalid': submitted && (errors.has('password') || serverErrors.password) }">

                                    <div v-if="submitted && (errors.has('password') || serverErrors.password)"
                                         class="invalid-feedback">
                                        {{ errors.first('password') }}
                                        <template v-for="error in serverErrors.password">{{ error }}</template>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="col d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary ml-3" :disabled="submitted">
                                        Login
                                    </button>
                                    <spinner v-show="loading"></spinner>
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
                    email: null,
                    password: null
                },
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
                'login',
            ]),

            onSubmit() {
                this.submitted = true;
                this.$validator.validate().then(valid => {
                    if (valid) {
                        this.submitted = false
                        this.loading = true
                        this.login(this.form)
                            .then(() => {
                                this.loading = false
                                if (!this.phone_verify) {
                                    this.$router.replace('/verificar/codigo')

                                    return;
                                }

                                this.$router.replace('/')
                            })
                            .catch((data) => {
                                this.loading = false
                                this.error = data.message
                                this.serverErrors = data.errors || {}
                            })
                    }
                });
            },

        },

        components: {
            Spinner
        }
    }
</script>

<style scoped>

</style>