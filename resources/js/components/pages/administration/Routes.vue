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

                    <template slot="table-title">All exchanges due that you have available</template>

                    <ye-actions slot="actions" slot-scope="{row}" class="text-center">
                        <router-link :to="'/exchange-due/receive/' + row.id" class="dropdown-item" title="Receive">
                            <i class="fal fa-list-alt"></i>
                            Receive
                        </router-link>
                    </ye-actions>

                    <template slot="filters-form">

                    </template>
                </ye-table>
            </div>
        </div>

    </box-user>
</template>

<script>
    import BoxUser from '../../layout/BoxUser'
    import { cloneDeep } from '../../modules/query-string'

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
                    'sale_order_item.sale_order.number',
                    'sale_order_item.sale_order.customer.name',
                    'sale_order_item.inventory_master.part_number',
                    'sale_order_item.inventory_master.description',
                    'return_serial_number',
                    'sale_order_item.qty',
                    'days_due',
                    'returned',
                    'received_by',
                    'actions',
                ],

                options: {
                    sortable: [
                        'sale_order_item.sale_order.number',
                        'sale_order_item.sale_order.customer.name',
                        'sale_order_item.inventory_master.part_number',
                        'sale_order_item.inventory_master.description',
                        'return_serial_number',
                        'sale_order_item.qty',
                        'days_due',
                        'returned',
                    ],
                    columnsClasses: {
                        'actions': 'action-col'
                    },
                    headings: {
                        'sale_order_item.sale_order.number': 'SO#',
                        'sale_order_item.sale_order.customer.name': 'Customer Name',
                        'sale_order_item.inventory_master.part_number': 'PN',
                        'sale_order_item.inventory_master.description': 'Description',
                        'return_serial_number': 'SN',
                        'sale_order_item.qty': 'Qty',
                        'days_due': 'Days Due',
                        'returned': 'Returned',
                        'received_by': 'Received By',
                        'actions': ''
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