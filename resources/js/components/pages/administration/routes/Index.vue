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
                        <li>
                            <router-link :to="{name: 'routes_edit', params: { id: row.id } }" class="dropdown-item"
                                         title="Edit">
                                <i class="fas fa-pen-square"></i>
                                Editar
                            </router-link>
                        </li>
                        <li>
                            <a href="#" class="dropdown-item" title="Eliminar" @click="onDelete(row.id)">
                                <i class="fas fa-trash-alt"></i>
                                Eliminar
                            </a>
                        </li>
                    </ye-actions>

                    <template slot="pre-header-buttons">
                        <router-link class="btn btn-success btn-sm --uppercase" :to="{name: 'routes_create'}">
                            Adicionar
                        </router-link>
                    </template>

                    <template slot="filters-form">

                    </template>
                </ye-table>
            </div>
        </div>

        <notifications group="index_route"/>
    </box-user>
</template>

<script>
    import BoxUser from '../../../layout/BoxUser'
    import {cloneDeep} from '../../../modules/query-string'
    import {mapState, mapActions} from 'vuex'
    import Swal from 'sweetalert2'

    export default {
        data() {
            return {
                filters: {
                    name: null,
                    formatted_address_start: null,
                    formatted_address_end: null,
                },

                columns: [
                    'name',
                    'formatted_address_start',
                    'formatted_address_end',
                    'actions',
                ],

                options: {
                    sortable: [
                        'name',
                        'formatted_address_start',
                        'formatted_address_end',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'name': 'Nombre',
                        'formatted_address_start': 'Origen',
                        'formatted_address_end': 'Destino',
                        'actions': 'Acciones'
                    }
                },

                defaultFilters: {}
            };
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),

            apiEndpoint() {
                return route('api.routes.index');
            }
        },

        methods: {
            ...mapActions([
                'deleteRoute',
            ]),

            onDelete(id) {
                this.serverErrors = {}
                Swal.fire({
                    title: 'Esta seguro que desea eliminar la ruta?',
                    text: 'Puedes cancelar la operación',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        this.deleteRoute(id)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'index_route',
                                    title: 'Ruta',
                                    text: 'Se ha eliminado la ruta correctamente'
                                });

                                this.reloadTable()
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'index_route',
                                    title: 'Ruta',
                                    text: 'Ha ocurrido un error al eliminar la ruta.'
                                });
                                this.serverErrors = data.errors || {}
                            })
                    }
                })
            },

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