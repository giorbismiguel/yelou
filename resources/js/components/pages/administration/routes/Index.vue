<template>
    <box-user>
        <h3>Rutas</h3>
        <hr>

        <div class="row justify-content-center">
            <div class="col">
                <ye-table id="table_routes"
                          :columns="columns"
                          :options="options"
                          :url="apiEndpoint"
                          ref="table"
                          :filters="filters"
                          @clearFilters="clearFilter">

                    <template slot="table-title">Todas las rutas disponibles</template>

                    <ye-actions slot="actions" slot-scope="{row}" class="text-center">
                        <router-link :to="''" class="dropdown-item" title="Edit">
                            <i class="fas fa-pen-square"></i>
                            In progress Editar
                        </router-link>

                        <li class="dropdown-item" title="Eliminar">
                            <i class="fas fa-trash-alt"></i>
                            In progress Eliminar
                        </li>
                    </ye-actions>

                    <template slot="pre-header-buttons">
                        <router-link class="btn btn-success btn-sm --uppercase" :to="{name: 'routes_create'}">
                            Nueva Ruta
                        </router-link>
                    </template>

                    <template slot="filters-form">

                    </template>
                </ye-table>
            </div>
        </div>

    </box-user>
</template>

<script>
    import BoxUser from '../../../layout/BoxUser'
    import {cloneDeep} from '../../../modules/query-string'

    export default {
        data() {
            return {
                request: {},

                filters: {
                    sale_order_number: null,
                    customer_name: null,
                    returned: null,
                    part_number: null,
                    description: null,
                    return_serial_number: null,
                },

                columns: [
                    'name',
                    'formatted_address',
                    'actions',
                ],

                options: {
                    sortable: [
                        'name',
                        'formatted_address',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'name': 'Nombre',
                        'formatted_address': 'Dirección',
                        'actions': 'Acciones'
                    }
                },

                defaultFilters: {}
            };
        },

        computed: {
            apiURL() {
                return route('api.routes.index', this.filters);
            },

            apiEndpoint() {
                return route('api.routes.index');
            }
        },

        methods: {
            clearFilter() {
                this.filters = cloneDeep(this.defaultFilters)
                this.$nextTick(this.reloadTable)
            },

            reloadTable() {
                return this.$refs['table'].applyFiltersAndReload()
            }
        },

        mounted() {
            this.defaultFilters = cloneDeep(this.filters)
        },

        components: {
            BoxUser
        }
    }
</script>

<style scoped>

</style>