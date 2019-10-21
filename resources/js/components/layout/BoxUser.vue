<template>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" v-if="me" :class="{'active' : activeSideBar}">
            <div class="sidebar-header">
                <h3>
                    <router-link :to="{ name: 'administration' }">Administración</router-link>
                </h3>
            </div>

            <ul class="list-unstyled components">
                <template v-if="me && me.type === 2">
                    <li class="active">
                        <router-link :to="{ name: 'tours' }">Recorridos</router-link>
                    </li>
                </template>

                <template v-if="me && me.type === 1">
                    <li class="active">
                        <router-link :to="{ name: 'services' }"><img src="/img/icons/services.png" alt="Servicios"
                                                                     width="21" height="21"/> Servicios
                        </router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'routes' }"><img src="/img/icons/routes.png" alt="Rutas" width="21"
                                                                   height="21"/> Rutas
                        </router-link>
                    </li>
                </template>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <button type="button" id="sidebarCollapse" class="btn btn-info" @click="toggleSidebar">
                        <i class="fas fa-align-left"></i>
                        <span>Toggle Sidebar</span>
                    </button>
                </div>
            </nav>
            <slot></slot>
        </div>

    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "BoxUser",

        data() {
            return {
                activeSideBar: false
            }
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),

            isCliente() {
                return this.me && this.me.type === 1;
            },

            isDriver() {
                return this.me && this.me.type === 2;
            }
        },

        methods: {
            toggleSidebar() {
                this.activeSideBar = !this.activeSideBar
            }
        }
    }
</script>

<style scoped>
    .wrapper {
        display: flex;
        align-items: stretch;
    }

    #content {
        width: 100%;
        padding: 20px;
        min-height: 100vh;
        transition: all 0.3s;
    }

    p {
        font-family: 'Poppins', sans-serif;
        font-size: 1.1em;
        font-weight: 300;
        line-height: 1.7em;
        color: #999;
    }

    a, a:hover, a:focus {
        color: inherit;
        text-decoration: none;
        transition: all 0.3s;
    }

    #sidebar {
        min-width: 250px;
        max-width: 250px;
        min-height: 100vh;
        color: #EAEAEA;
        transition: all 0.3s;
        background: #2089C0;
    }

    #sidebar.active {
        margin-left: -250px;
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: #005E8F;
        font-family: 'Montserrat-Bold', sans-serif;
        font-size: 24px;
    }

    #sidebar ul.components {
        padding: 20px 0;
        border-bottom: 1px solid #47748b;
    }

    #sidebar ul p {
        color: #fff;
        padding: 10px;
    }

    #sidebar ul li a {
        padding: 10px;
        font-size: 1.1em;
        display: block;
    }

    #sidebar ul li a:hover {
        color: #7386D5;
        background: #fff;
    }

    #sidebar ul li.active > a, a[aria-expanded="true"] {
        color: #fff;
    }

    ul ul a {
        font-size: 0.9em !important;
        padding-left: 30px !important;
        background: #6d7fcc;
    }

    @media (max-width: 768px) {
        #sidebar {
            margin-left: -250px;
        }

        #sidebar.active {
            margin-left: 0;
        }
    }
</style>