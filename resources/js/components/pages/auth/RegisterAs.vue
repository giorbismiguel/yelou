<template>
    <div class="row justify-content-center">
        <div class="col">
            <router-link :to="{ name: 'register', params: { type: 'chofer' } }">
                <div class="card-register d-flex align-items-center justify-content-around flex-colum">
                    <div class="d-flex flex-column card-register-header">
                        <span>Quieres conducir?</span>
                        <strong>Regístrate</strong>
                    </div>
                    <div class="card-register-footer">
                        <i class="fas fa-3x fa-car"></i>
                    </div>
                </div>
            </router-link>
        </div>
        <div class="col">
            <router-link :to="{ name: 'register', params: { type: 'cliente' }  }">
                <div class="card-register d-flex align-items-center justify-content-around flex-colum">
                    <div class="d-flex flex-column card-register-header">
                        <span>Viajar seguro?</span>
                        <strong>Regístrate</strong>
                    </div>
                    <div class="card-register-footer">
                        <i class="fas fa-3x fa-route"></i>
                    </div>
                </div>
            </router-link>
        </div>
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
        margin: 40px;
    }

    .card-register-header {
        min-width: 160px;
        color: rgb(0, 66, 100);
        font-weight: normal;
        padding: .5em;
    }

    .card-register-header span {
        font-size: 32px;
        font-family: 'Montserrat', sans-serif;
    }

    .card-register-footer {
        color: rgb(0, 66, 100);
    }

    a:hover {
        text-decoration: none;
    }

    @media (max-width: 991.98px) {
        .card-register-header span {
            font-size: 28px;
        }
    }
</style>