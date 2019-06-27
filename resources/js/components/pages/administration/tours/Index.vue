<template>
    <box-user>
        <h3>Recorridos</h3>
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

                    <template slot="table-title">Todos sus recorridos solicitados</template>

                    <template slot="client_id" slot-scope="{row}">
                        {{ row.client.name }}
                    </template>

                    <template slot="service_id" slot-scope="{row}">
                        <strong class="app_color_blue">Origen:</strong>
                        {{ row.service.name_start }}
                        y
                        <strong class="app_color_blue">Destino:</strong>
                        {{ row.service.name_end }}
                    </template>

                    <template slot="status_id" slot-scope="{row}">
                        {{ row.status.name }}
                    </template>

                    <template slot="created_at" slot-scope="{row}">
                        {{ row.created_at }}
                    </template>

                    <ye-actions slot="actions" slot-scope="{row}" class="text-center">
                        <li v-if="false">
                            <router-link :to="{name: 'services_edit', params: { id: row.id } }" class="dropdown-item"
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

                    <template slot="filters-form"></template>
                </ye-table>
            </div>
        </div>

        <notifications group="index_request_services" position="bottom right"/>
    </box-user>
</template>

<script>
    import BoxUser from '../../../layout/BoxUser'
    import {cloneDeep} from '../../../modules/query-string'
    import {mapState, mapActions} from 'vuex'
    import Swal from 'sweetalert2'
    import DatePicker from 'vue2-datepicker'

    export default {
        name: "Tours",

        data() {
            return {
                filters: {
                    start_time: null,
                    name_start: null,
                    name_end: null,
                    payment_method_id: null
                },

                columns: [
                    'client_id',
                    'service_id',
                    'status_id',
                    'created_at',
                    'actions',
                ],

                options: {
                    sortable: [
                        'client_id',
                        'service_id',
                        'status_id',
                        'created_at',
                    ],

                    columnsClasses: {
                        'actions': 'action-col'
                    },

                    headings: {
                        'client_id': 'Cliente',
                        'service_id': 'Servicio solicitado',
                        'status_id': 'Estado',
                        'created_at': 'Fecha',
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
                return route('api.requested_services.index');
            }
        },

        methods: {
            ...mapActions([
                'deleteRoute',
            ]),

            onDelete(id) {
                this.serverErrors = {}
                Swal.fire({
                    title: 'Esta seguro que desea eliminar el ?',
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