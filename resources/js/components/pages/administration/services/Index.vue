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

                    <template slot="start_time" slot-scope="{row}">
                        {{ row.start_time == '00:00:00' ? '' : row.start_time }}
                    </template>

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
                            <a href="#" class="dropdown-item" title="Eliminar" @click="cancelService(row.id)">
                                <i class="fas fa-ban"></i>
                                Cancelar
                            </a>
                        </li>

                        <li>
                            <a href="#" class="dropdown-item" title="Eliminar" @click="showDriverModal(row)">
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
                  :okHidden="true" @cancel="hideDriverModel">
            <div class="row">
                <div class="col-md-12">
                    <ye-table id="table_requested_services"
                              :columns="columnsDrivers"
                              :options="optionsDrivers"
                              :url="apiEndpointDrivers"
                              ref="tableDrivers"
                              :filters="filtersDrivers"
                              @clearFilters="clearFilterDrivers">

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

        <notifications group="index_requested_services" position="bottom right"/>
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
                    start_date: null,
                    start_time: null,
                    name_start: null,
                    name_end: null,
                    payment_method_id: null
                },

                columns: [
                    'start_date',
                    'start_time',
                    'name_start',
                    'name_end',
                    'payment_method_id',
                    'actions',
                ],

                options: {
                    sortable: [
                        'start_date',
                        'start_time',
                        'name_start',
                        'name_end',
                        'payment_method_id',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'start_date': 'Día',
                        'start_time': 'Hora de Inicio',
                        'name_start': 'Origen',
                        'name_end': 'Destino',
                        'payment_method_id': 'Medio de pago',
                        'actions': 'Acciones',
                    }
                },

                defaultFilters: {},

                filtersDrivers: {
                    client_id: null,
                    status_id: 1,
                    service_id: null,
                },

                columnsDrivers: [
                    'transporter_id',
                    'actions',
                ],

                optionsDrivers: {
                    columnsClasses: {
                        'actions': 'action-col'
                    },

                    headings: {
                        'transporter_id': 'Nombre',
                        'actions': 'Acciones',
                    }
                },

                defaultFiltersDrivers: {},

                apiEndpointDrivers: ''
            };
        },

        computed: {
            ...mapState({
                me: state => state.auth.me,
            }),

            apiEndpoint() {
                return route('api.request_services.index');
            }
        },

        methods: {
            ...mapActions([
                'deleteRequestService',
                'acceptDriverService'
            ]),

            hideDriverModel() {
                this.modal.showDriver = false
            },

            showDriverModal(row) {
                this.getEndpointDrivers(row);
                this.filtersDrivers.client_id = this.me.id
                this.filtersDrivers.service_id = row.id
                this.modal.showDriver = true
                this.reloadTableDrivers()
            },

            onDeleteService(id) {
                this.serverErrors = {}

                Swal.fire({
                    title: 'Esta seguro que desea eliminar el servicio?',
                    text: 'Puedes cancelar la operación',
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Eliminar',
                    cancelButtonText: 'Cancelar',
                    showCloseButton: true,
                    showLoaderOnConfirm: true
                }).then((result) => {
                    if (result.value) {
                        this.deleteRequestService(id)
                            .then(() => {
                                this.loading = false
                                this.$notify({
                                    type: 'success',
                                    group: 'index_requested_services',
                                    title: 'Ruta',
                                    text: 'Se ha eliminado el servicio'
                                });

                                this.reloadTable()
                            })
                            .catch((data) => {
                                this.loading = false
                                this.$notify({
                                    type: 'error',
                                    group: 'index_requested_services',
                                    title: 'Ruta',
                                    text: 'Ha ocurrido un error al eliminar el servicio'
                                });
                                this.serverErrors = data.errors || {}
                            })
                    }
                })
            },

            cancelService() {

            },

            clearFilter() {
                this.filters = cloneDeep(this.defaultFilters)
                this.$nextTick(this.reloadTable)
            },

            clearFilterDrivers() {
                this.filters = cloneDeep(this.defaultFiltersDrivers)
                this.$nextTick(this.reloadTable)
            },

            reloadTable() {
                return this.$refs['table'].applyFiltersAndReload()
            },

            reloadTableDrivers() {
                return this.$refs['tableDrivers'].applyFiltersAndReload()
            },

            acceptService(id) {
                this.acceptDriverService(id)
                    .then(() => {
                        this.hideDriverModel()
                        this.$notify({
                            type: 'success',
                            group: 'index_requested_services',
                            title: 'Servicios',
                            text: 'Se le ha enviado una notificación al chofer'
                        })

                        this.reloadTableDrivers()
                    })
                    .catch((data) => {
                        this.hideDriverModel()
                        this.$notify({
                            type: 'error',
                            group: 'index_requested_services',
                            title: 'Servicios',
                            text: 'Ha ocurrido un error al enviar la notificacion chofer'
                        });
                        this.reloadTableDrivers()
                    })
            },

            getEndpointDrivers() {
                this.apiEndpointDrivers = `${route('api.requested_services.index')}`;
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