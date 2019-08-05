<template>
    <box-user>
        <loading :active.sync="isLoading"
                 :can-cancel="true"
                 :is-full-page="true"></loading>

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

                        <li>
                            <a :href="urlPrintInvoice(row.id)" class="dropdown-item" title="Imprimir Factura" target="_blank">
                                <i class="fas fa-print"></i>
                                Imprimir Factura
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
                            {{ row.transporter.first_name + ' ' + row.transporter.last_name }}
                        </template>

                        <template slot="phone" slot-scope="{row}">
                            {{ row.transporter.phone }}
                        </template>

                        <template slot="photo" slot-scope="{row}">
                            <img :src="(row.transporter.photo ? '/storage/' + row.transporter.photo : '/img/default-image.png')"
                                 alt="Foto del Chofer" style="width: 200px; height: 200px;">
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
    import Spinner from 'vue-simple-spinner'
    import Loading from 'vue-loading-overlay';

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
                    'name_start',
                    'name_end',
                    'payment_method_id',
                    'start_date',
                    'start_time',
                    'actions',
                ],

                options: {
                    sortable: [
                        'name_start',
                        'name_end',
                        'payment_method_id',
                        'start_date',
                        'start_time',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'name_start': 'Origen',
                        'name_end': 'Destino',
                        'payment_method_id': 'Medio de pago',
                        'start_date': 'Día',
                        'start_time': 'Hora de Inicio',
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
                    'phone',
                    'photo',
                    'actions',
                ],

                optionsDrivers: {
                    columnsClasses: {
                        'actions': 'action-col'
                    },

                    headings: {
                        'transporter_id': 'Nombre',
                        'phone': 'Teléfono',
                        'photo': 'Foto',
                        'actions': 'Acciones',
                    }
                },

                defaultFiltersDrivers: {},

                apiEndpointDrivers: route('api.requested_services.index'),

                isLoading: false
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
                this.getEndpointDrivers();
                this.filtersDrivers.client_id = this.me.id
                this.filtersDrivers.service_id = row.id
                this.reloadTableDrivers()
                this.modal.showDriver = true
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
                                this.$notify({
                                    type: 'success',
                                    group: 'index_requested_services',
                                    title: 'Ruta',
                                    text: 'Se ha eliminado el servicio'
                                });

                                this.reloadTable()
                            })
                            .catch((data) => {
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
                this.hideDriverModel()
                this.isLoading = true
                this.acceptDriverService(id)
                    .then(() => {
                        this.isLoading = false
                        this.$notify({
                            type: 'success',
                            group: 'index_requested_services',
                            title: 'Servicios',
                            text: 'Se le ha enviado una notificación al chofer'
                        })
                        this.reloadTableDrivers()
                    })
                    .catch(() => {
                        this.isLoading = false
                        this.$notify({
                            type: 'error',
                            group: 'index_requested_services',
                            title: 'Servicios',
                            text: 'Ha ocurrido un error al enviar la notificacion chofer'
                        });
                    })
            },

            getEndpointDrivers() {
                this.apiEndpointDrivers = `${route('api.requested_services.index')}`;
            },

            urlPrintInvoice(id) {
                return route('invoice.client.print', id);
            }
        },

        mounted() {
            this.defaultFilters = cloneDeep(this.filters)
        },

        components: {
            BoxUser,
            Spinner,
            Loading
        }
    }
</script>

<style scoped>

</style>