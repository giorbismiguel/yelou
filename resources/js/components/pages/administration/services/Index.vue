<template>
    <box-user>
        <h3>Servicios</h3>
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

                    <template slot="table-title">Todos los servicios que fueron solicitados</template>
                    <template slot="payment_method_id" slot-scope="{row}">
                        {{ row.payment_method.name }}
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
                            <a href="#" class="dropdown-item" title="Eliminar" @click="onDeleteService(row.id)">
                                <i class="fas fa-trash-alt"></i>
                                Eliminar
                            </a>
                        </li>

                        <li>
                            <a href="#" class="dropdown-item" title="Eliminar" @click="showDriverModal">
                                <i class="fas fa-users"></i>
                                Choferes disponibles
                            </a>
                        </li>
                    </ye-actions>

                    <template slot="pre-header-buttons">
                        <router-link class="btn btn-accept" :to="{name: 'services_create'}">
                            Solicitar Servicio
                        </router-link>
                    </template>

                    <template slot="filters-form">

                    </template>
                </ye-table>
            </div>
        </div>

        <ye-modal id="modalAddresses" :title="modal.title" :show="modal.showDriver" size="large" cancel-text="Cerrar"
                  okHidden="true" @cancel="hideDriverModel" @ok="save">
            <div class="row">
                <div class="col-md-12">
                    <ye-table id="table_requested_services"
                              :columns="columnsDriver"
                              :options="optionsDriver"
                              :url="apiEndpointDriver"
                              ref="table"
                              :filters="filtersDrivers"
                              @clearFilters="clearFilterDriver">

                        <template slot="table-title">Todas las solicitudes para prestarle el servicio</template>

                        <template slot="transporter_id" slot-scope="{row}">
                            {{ row.transporter.name }}
                        </template>

                        <ye-actions slot="actions" slot-scope="{row}" class="text-center">
                            <li>
                                <a href="#" class="dropdown-item" title="Aceptar Servicio"
                                   @click="acceptService(row.id)">
                                    <i class="fas fa-car-alt"></i>
                                    Aceptar
                                </a>
                            </li>
                        </ye-actions>

                        <template slot="filters-form"></template>
                    </ye-table>
                </div>
            </div>
        </ye-modal>

        <notifications group="index_request_services"/>
    </box-user>
</template>

<script>
    import BoxUser from '../../../layout/BoxUser'
    import {cloneDeep} from '../../../modules/query-string'
    import {mapState, mapActions} from 'vuex'
    import Swal from 'sweetalert2'

    export default {
        name: "Services",

        data() {
            return {
                modal: {
                    title: 'Choferes disponibles para prestar el servicio',
                    showDriver: false,
                },

                filters: {
                    start_time: null,
                    name_start: null,
                    name_end: null,
                    payment_method_id: null
                },

                columns: [
                    'start_time',
                    'name_start',
                    'name_end',
                    'payment_method_id',
                    'actions',
                ],

                options: {
                    sortable: [
                        'start_time',
                        'name_start',
                        'name_end',
                        'payment_method_id',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'start_time': 'Hora de Inicio',
                        'name_start': 'Origen',
                        'name_end': 'Destino',
                        'payment_method_id': 'Medio de pago',
                        'actions': 'Acciones',
                    }
                },

                defaultFilters: {},

                filtersDriver: {
                    start_time: null,
                    name_start: null,
                    name_end: null,
                    payment_method_id: null
                },

                columnsDriver: [
                    'transporter_id',
                    'actions',
                ],

                optionsDriver: {
                    columnsClasses: {
                        'actions': 'action-col'
                    },

                    headings: {
                        'transporter_id': 'Nombre',
                        'actions': 'Acciones',
                    }
                },

                defaultFiltersDriver: {}
            };
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),

            apiEndpoint() {
                return route('api.request_services.index');
            },

            apiEndpointDriver() {
                return route('api.requested_services.index');
            }
        },

        methods: {
            ...mapActions([
                'deleteService',
                'acceptDriverService'
            ]),

            hideDriverModel() {
                this.modal.showDriver = false
            },

            showDriverModal() {
                this.modal.showDriver = true
            },

            onDeleteService(id) {
                return;

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
            },

            acceptService(id) {
                this.acceptDriverService(id);
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