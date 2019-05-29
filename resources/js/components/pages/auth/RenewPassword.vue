<template>
    <div class="container">
        <h3>Autenticarse</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col-5">
                <div class="card m-4">
                    <div class="card-header">Autenticarse</div>
                    <div class="card-body">
                        <div class="alert alert-danger text-center" v-if="error">
                            {{ error }}
                        </div>

                        <form id="login_form" class="form-horizontal" role="form" @submit.prevent="onSubmit"
                              autocomplete="off">

                            <div class="form-group">
                                <label for="email" class="col control-label">Correo electrónico o Teléfono</label>
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
                                <div class="col d-flex justify-content-center">
                                    <button type="submit" class="btn btn-primary ml-3 btn-"
                                            :disabled="disabledResetButton">
                                        Restablecer
                                    </button>
                                    <spinner v-show="loading" size="medium"></spinner>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col text-center">
                                    <router-link class="btn btn-link px-0" :to="{ name: 'login'}">
                                        ¿Olvidó su contraseña?
                                    </router-link>
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
                disabledResetButton: false,
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
                'find_token',
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
        mounted() {
            if (this.$route.params.token) {
                this.disabledResetButton = false;
                this.find_token(this.$route.params.token)
                    .then(() => {
                        this.disabledResetButton = true;
                        this.loading = false
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