<template>
    <div class="wrapper">

        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>
                    <router-link :to="{ name: 'administration' }">Administración</router-link>
                </h3>
            </div>

            <ul class="list-unstyled components">
                <template v-if="me.type === 2">
                    <li>
                        <router-link :to="{ name: 'administration' }">Recorrido(En desarrollo)</router-link>
                    </li>
                </template>

                <template v-if="me.type === 1">
                    <li>
                        <router-link :to="{ name: 'services' }">Servicios</router-link>
                    </li>
                    <li>
                        <router-link :to="{ name: 'routes' }">Rutas</router-link>
                    </li>
                </template>
            </ul>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <slot></slot>
        </div>

    </div>
</template>

<script>
    import {mapState} from 'vuex'

    export default {
        name: "BoxUser",

        computed: {
            ...mapState({
                me: state => state.auth.me,
            })
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
        background: #7386D5;
        color: #fff;
        transition: all 0.3s;
    }

    #sidebar .sidebar-header {
        padding: 20px;
        background: #6d7fcc;
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
        background: #6d7fcc;
    }

    ul ul a {
        font-size: 0.9em !important;
        padding-left: 30px !important;
        background: #6d7fcc;
    }
</style>