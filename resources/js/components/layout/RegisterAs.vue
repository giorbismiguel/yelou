<template>
    <div class="row">
        <router-link :to="{ name: 'register', params: { type: 'chofer' } }">
            <div class="col d-flex">
                <div class="card-register d-flex align-items-center justify-content-around flex-colum">
                    <div class="d-flex flex-column card-register-header">
                        <span>Quieres conducir?</span>
                        <strong>Regístrate</strong>
                    </div>
                    <div class="card-register-footer">
                        <i class="fas fa-3x fa-car"></i>
                    </div>
                </div>
            </div>
        </router-link>

        <router-link :to="{ name: 'register', params: { type: 'cliente' }  }">
            <a href="">
                <div class="col">
                    <div class="card-register d-flex align-items-center justify-content-around flex-colum">
                        <div class="d-flex flex-column card-register-header">
                            <span>Viajar seguro?</span>
                            <strong>Regístrate</strong>
                        </div>
                        <div class="card-register-footer">
                            <i class="fas fa-3x fa-route"></i>
                        </div>
                    </div>
                </div>
            </a>
        </router-link>
    </div>
</template>

<script>
    export default {
        name: "RegisterAs",
    }
</script>

<style scoped>
    .card-register {
        border-radius: 8px 8px 8px 8px;
        border: 1px solid rgb(0, 66, 100);
        height: 116px;
        width: 447px;
        margin: 40px;
    }

    .card-register-header {
        width: 311px;
        color: rgb(0, 66, 100);
        font-weight: normal;
    }

    .card-register-header span {
        font-size: 32px;
        font-family: 'Montserrat';
    }

    .card-register-footer {
        color: rgb(0, 66, 100);
    }

    a:hover {
        text-decoration: none;
    }
</style>