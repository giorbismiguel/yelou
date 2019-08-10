<template>
    <div class="simple-table" :data-is-loading="isLoading ? 'true' : 'false'">
        <div class="row simple-table__before-header no-padding">
            <div class="col-sm-12 col-md-4 simple-table__before-header__per-page">
                <slot name="pre-header-per-page">
                    <label>Mostrar</label>
                    <select v-model="localPerPage" class="custom-select custom-select-sm" :disabled="rows.length === 0">
                        <option v-for="perPageItem in perPageList" :value="perPageItem">{{ perPageItem }}</option>
                    </select>
                    <label>registros</label>
                </slot>
            </div>
            <div class="col-sm-12 col-md-8 text-right" style="padding-top: 4px;">
                <slot name="pre-header-buttons"></slot>
            </div>
        </div>
        <div class="simple-table__container no-padding">
            <div class="row no-gutters simple-table__header">
                <div class="col simple-table__title">
                    <slot name="table-title"></slot>
                </div>
                <div class="col simple-table__buttons text-right">
                    <button class="btn font-bold no-padding-right --btn-filters --icon-right" v-if="hasFiltersSlot"
                            @click="toggleFilters()" data-test="toggle_filters">
                        {{ showFilters ? 'Hide' : 'Open' }} Filters <i class="far fa-sliders-h"></i>
                    </button>
                </div>
            </div>
            <transition name="fade">
                <div class="simple-table__filters" v-if="hasFiltersSlot" v-show="showFilters">
                    <div class="row simple-table__filters__header">
                        <div class="col simple-table__filters__header__title">
                            Filtros
                        </div>
                    </div>
                </div>
            </transition>
            <div class="row no-gutters">
                <div class="col">
                    <table class="table table-borderless table-striped table-hover">
                        <thead>
                        <tr>
                            <th v-if="hasChildren" style="width: 25px;">
                                <i class="fal"/>
                            </th>
                            <th v-for="column in columns"
                                :class="getHeaderClass(column)"
                                :data-col="column"
                                @click="setOrderBy(column)">
                                <span class="simple-table__heading --no-select">
                                    <slot :name="`header-${column}`" v-bind:column="column">
                                        {{ get(options.headings, column, prepareHeading(column)) }}
                                    </slot>
                                </span>
                                <span v-if="isSorteable(column)"
                                      class="simple-table__sort-icon --no-select float-right d-flex">
                                    <i class="fas fa-long-arrow-alt-up mr-1"
                                       :class="{'active': isOrderAndSortBy(column, 'asc')}"></i>
                                    <i class="fas fa-long-arrow-alt-down"
                                       :class="{'active': isOrderAndSortBy(column, 'desc')}"></i>
                                </span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-show="showLoadingProgress" data-loading>
                            <td :colspan="columnsLength+1">
                                <spinner></spinner>
                            </td>
                        </tr>

                        <template v-for="(row, index) in rows">
                            <simple-table-row :row="row" :position="index" :child="getChildren"
                                              v-bind="getRowDataAttrs(row)"
                                              v-show="!showLoadingProgress">
                                <td v-for="column in columns" :class="getColumnClass(column)" :data-col="column">
                                    <slot :name="column" v-bind:row="row">
                                        {{ get(row, column) }}
                                    </slot>
                                </td>

                                <slot slot="header.extra-columns" name="children.extra-headers" v-bind:row="row"></slot>
                                <slot slot="body.extra-columns" name="children.extra-columns" v-bind:row="row"></slot>
                            </simple-table-row>
                        </template>
                        <tr v-if="!showLoadingProgress && rows.length === 0">
                            <td :colspan="columnsLength" class="text-center">
                                <slot name="empty_table">
                                    <div class="simple-table__no_data">No hay datos para mostrar</div>
                                </slot>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-show="!showLoadingProgress" v-if="showPagination && total > 0"
             class="row simple-table__pagination-footer">
            <slot name="pagination" v-bind:data="{total, curPage, localPerPage, paginateFrom, paginateTo}">
                <div class="col simple-table__pagination-footer__label">
                    <slot name="pagination-label"
                          v-bind:data="{total, curPage, localPerPage, paginateFrom, paginateTo}">
                        Mostrando de {{ paginateFrom }} a {{ paginateTo }} de {{ total }} registros
                    </slot>
                </div>
                <div class="col">
                    <slot name="pagination-paginate"
                          v-bind:data="{total, curPage, localPerPage, paginateFrom, paginateTo}">
                        <paginate v-model="curPage" :total-count="total" :per-page="localPerPage"></paginate>
                    </slot>
                </div>
            </slot>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import has from 'lodash/has'
    import get from 'lodash/get'
    import orderBy from 'lodash/orderBy'
    import isEmpty from 'lodash/isEmpty'
    import Paginate from './av-paginate'
    import Spinner from 'vue-simple-spinner'
    import SimpleTableRow from './simple-table-row'
    import queryStringify from './query-string'

    export default {
        name: 'SimpleTable',

        props: {
            columns: {
                type: Array,
                default: () => ([])
            },
            data: {
                type: Array,
                default: () => ([])
            },
            options: {
                type: Object,
                default: () => ({})
            },
            url: {
                type: String,
                default: null
            },
            clientPagination: {
                type: Boolean,
                default: false
            },
            showPagination: {
                type: Boolean,
                default: true
            },
            perPage: {
                type: Number,
                default: 10
            },
            currentPage: {
                type: Number,
                default: 1
            },
            filters: {
                type: Object,
                default: () => ({})
            },
            showLoading: {
                type: Boolean,
                default: true
            },
            orderBy: {
                type: String,
                default: null
            },
            sortBy: {
                type: String,
                default: 'asc'
            },
            rowDataAttrs: {
                type: Array,
                default: () => ([])
            },
            excelFields: {
                type: Object,
                default: null
            },
            pdfFields: {
                type: Object,
                default: null
            },
            excelExportRoute: {
                type: String,
                default: null
            },
            pdfExportRoute: {
                type: String,
                default: null
            },
            exportFixColumn: {
                type: String,
                default: null
            },
            canExport: {
                type: Boolean,
                default: false
            },
            openModalPDf: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                isLoading: true,
                curPage: this.currentPage,
                localPerPage: this.perPage,
                localData: this.data,
                total: this.data.length,
                localOrderBy: this.orderBy,
                localSortBy: this.sortBy.toLowerCase(),
                perPageList: [10, 25, 50, 100],
                appliedFilters: {},
                showFilters: false
            }
        },

        watch: {
            // Triggered when pagination change
            curPage() {
                if (this.useRemoteForData && !this.manualCurPage) {
                    this.fetchData();
                }
                this.manualCurPage = false;
            },

            localOrderBy(value) {
              this.$emit('changed-order-by', this.options.sortable.indexOf(value))
            },

            perPage(newValue) {
                this.localPerPage = newValue
            },

            data(newValue, oldValue) {
                this.localData = newValue

                if (!isEmpty(oldValue)) {
                    this.total = this.localData.length
                    if (this.curPage > Math.ceil(this.total / this.localPerPage)) {
                        this.curPage = 1
                    }
                    return
                }

                this.setPaginationData(this.localData.length, 1)
            },

            localPerPage() {
                this.curPage = 1
                if (this.useRemoteForData) {
                    this.fetchData()
                }
            },
        },

        computed: {
            sortingData() {
                return { orderBy: this.localOrderBy, sortBy: this.localSortBy }
            },

            isValidUrl() {
                return !!this.url
            },

            hasChildren() {
                return this.options.children
            },

            getChildren() {
                return this.hasChildren ? this.options.children : null
            },

            hasAnyFilter() {
                return Object.entries(this.filters).some(param => !!param[1]);
            },

            useRemoteForData() {
                return this.isValidUrl && !this.clientPagination
            },

            rows() {
                // Use data feed from remote URL
                if (this.useRemoteForData) {
                    return this.localData
                }
                // Use from local data and apply ordering + pagination
                const data = orderBy(this.localData, [this.localOrderBy], [this.localSortBy])

                const offset = (this.curPage - 1) * this.localPerPage
                return data.slice(offset, offset + this.localPerPage)
            },

            showLoadingProgress() {
                return this.isLoading && this.showLoading
            },

            paginateFrom() {
                return ((this.curPage - 1) * this.localPerPage) + 1
            },

            paginateTo() {
                const to = this.paginateFrom + this.localPerPage
                return to > this.total ? this.total : to
            },

            hasFiltersSlot() {
                return !!this.$slots['filters-form']
            },

            columnsLength() {
                return this.options.children &&
                this.options.children.params &&
                Object.keys(this.options.children.params).length > this.columns.length ?
                    Object.keys(this.options.children.params).length :
                    this.columns.length
            }
        },

        methods: {
            prepareHeading(str) {
                return (str + '')
                    .replace('.', ' ')
                    .replace(/^(.)|\s+(.)/g, function ($1) {
                        return $1.toUpperCase()
                    })
            },

            get(object, keys, defaultVal = null) {
                const value = get(object, keys, defaultVal)

                if (value === true) {
                    return 'Yes'
                }

                if (value === false) {
                    return 'No'
                }

                return value
            },

            applyFiltersAndFetchData() {
                this.manualCurPage = this.curPage !== 1;
                this.appliedFilters = queryStringify.cloneDeep(this.filters);
                this.curPage = 1;
                this.fetchData();
            },

            fetchData() {
                const queryString = null

                let url
                let separation = this.url.indexOf('?') > 0 ? '&' : '?'

                if (this.clientPagination) {
                    url = `${this.url}${queryString ? separation + queryString : ''}`
                } else {
                    const orderByQuery = this.orderBy || this.localOrderBy ? `&order_by=${this.localOrderBy}&sort_by=${this.localSortBy}` : ''
                    url = `${this.url}${separation}page=${this.curPage}&per_page=${this.localPerPage}${orderByQuery}${queryString ? '&' + queryString : ''}`
                    separation = '&'
                }

                this.isLoading = true
                const extraQueryString = Object.keys(this.appliedFilters).length > 0
                    ? separation + queryStringify.stringify(this.appliedFilters)
                    : ''

                axios.get(url + extraQueryString)
                    .then(({ data }) => {
                        this.localData = data.data

                        if (this.clientPagination) {
                            const to = this.localData.length > this.localPerPage
                                ? this.localPerPage
                                : this.localData.length
                            this.setPaginationData(this.localData.length, 1, 1, to)
                        } else {
                            this.setPaginationData(data.total, data.current_page, data.from, data.to)
                        }
                        this.isLoading = false
                        this.$emit('loadedData', this.localData)
                    })
                    .catch(() => this.isLoading = false)
            },

            setPaginationData(total, curPage) {
                this.total = total
                this.curPage = curPage
            },

            setOrderBy(column) {
                if (!this.isSorteable(column)) {
                    return
                }

                this.localSortBy = this.isOrderBy(column) && this.localSortBy.toLowerCase() === 'asc' ? 'desc' : 'asc'
                this.localOrderBy = column

                // Fetch from remote with the updated order + sort
                let oldCurPage = this.curPage
                this.curPage = 1;
                this.currentPage = 1;
                if (oldCurPage == 1) {
                    if (this.useRemoteForData) {
                        this.fetchData()
                    }
                }
            },

            isOrderBy(column) {
                return this.localOrderBy === column
            },

            isSorteable(column) {
                return get(this.options, 'sortable', []).includes(column) && this.rows.length > 0
            },

            isOrderAndSortBy(column, sort) {
                return this.isOrderBy(column) && this.localSortBy.toLowerCase() === sort
            },

            getHeaderClass(column) {
                let className = get(this.options, `headersClasses.${column}`, '')
                className = className + ' ' + (this.isSorteable(column) ? 'simple-table__sorteable' : '')
                className = className + ' ' + this.getColumnClass(column)

                return className.replace(/\s+/g, ' ').trim()
            },

            getColumnClass(column) {
                let className = get(this.options, `columnsClasses.${column}`, '')
                className = className + ' ' + (this.isOrderBy(column) && this.rows.length > 0 ? 'simple-table__sorted' : '')

                return className.replace(/\s+/g, ' ').trim()
            },

            getRowDataAttrs(data) {
                let attrs = {}
                this.rowDataAttrs.map(attrName => {
                    if (has(data, attrName)) {
                        attrs[`data-${attrName.replace('.', '-')}`] = get(data, attrName)
                    }
                })

                return attrs
            },

            applyFiltersAndReload() {
                this.appliedFilters = queryStringify.cloneDeep(this.filters)
                return this.reload()
            },

            reload() {
                return this.fetchData()
            },

            toggleFilters() {
                this.showFilters = !this.showFilters
            },

            clearFilters() {
                this.$emit('clearFilters')
            }
        },

        mounted() {
            // If initial url has been set then fetch data
            if (this.isValidUrl) {
                this.fetchData()

                return;
            }

            this.isLoading = false
        },

        components: {
            SimpleTableRow,
            Paginate,
            Spinner
        }
    }
</script>

<style>
    .fade-enter-active {
        transition: opacity .5s;
    }

    .fade-enter /* .fade-leave-active below version 2.1.8 */
    {
        opacity: 0;
    }

    .simple-table__before-header {
        margin-bottom: 15px;
    }

    .simple-table__before-header__per-page label,
    .simple-table__before-header__per-page select {
        width: auto;
        float: left;
    }

    .simple-table__before-header__per-page label {
        padding-top: 8px;
    }

    .simple-table__before-header__per-page select {
        margin: 2px 9px;
    }

    .simple-table__header {
        padding: 13px;
        align-items: center;
    }

    .simple-table__title {
        color: #4A4A4A;
        font-family: "Istok Web", "Helvetica Neue", Helvetica, Arial, sans-serif;
        font-size: 15px;
        font-weight: bold;
        line-height: 20px;
        padding-left: 6px !important;
    }

    .simple-table__container {
        border-radius: 2px;
        background-color: #FFFFFF;
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1);
    }

    .simple-table__filters {
        border-top: 1px solid #D8D8D8;
        padding: 20px;
    }

    .simple-table__filters__form {
        border-radius: 2px;
        background-color: #F6F6F8;
        padding: 25px 5px 10px 5px;
        margin-bottom: 20px !important;
    }

    .simple-table__filters__header {
        margin-bottom: 10px;
    }

    .simple-table__filters__header__title {
        font-family: 'Istok Web', 'open sans', sans-serif;
        font-size: 25px;
        color: #000000;
        letter-spacing: -0.5px;
        margin-left: -2px;
    }

    .simple-table__filters__buttons .btn {
        font-size: 12px;
        padding: 6px 30px;
        margin: 0 10px;
    }

    .simple-table__buttons {
        padding-right: 6px !important;
    }

    .simple-table__buttons .btn {
        margin-left: 12px;
    }

    .simple-table__buttons .btn:active {
        box-shadow: none;
    }

    .simple-table__heading {
        cursor: pointer;
        padding-top: 2px;
        font-weight: 400;
        letter-spacing: 0.2px;
    }

    .simple-table__sorteable {
        cursor: pointer;
    }

    .simple-table__sort-icon {
        padding-top: 1px;
        width: 15px;
        text-align: center;
    }

    .simple-table__sort-icon i:first-child {
        margin-right: -3px;
    }

    .simple-table__sort-icon i {
        opacity: 0.5;
        color: #FFFFFF;
    }

    .simple-table__sort-icon i.active {
        opacity: 1;
    }

    .simple-table__pagination-footer {
        padding-top: 15px;
    }

    .simple-table__pagination-footer__label {
        font-size: 13px;
        padding-top: 5px;
    }

    .simple-table__no_data {
        padding-top: 12px;
    }

    .simple-table .pagination {
        float: right !important;
    }

    .dropdown a {
        display: block;
        padding: 5px 10px;
    }
</style>
