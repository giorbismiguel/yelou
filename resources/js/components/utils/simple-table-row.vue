<template>
    <component is="tr" :class="{expanded: !isCollapsed}">
        <td style="cursor:pointer;" @click="toggleCollapsed" v-if="hasKey" data-test="toggle_child" width="1%">
            <i class="fal" v-show="hasChildren" :class="collapseIndicator"></i>
        </td>
        <slot></slot>
        <template v-if="hasChildren">
            <tr v-show="!isCollapsed">
                <td colspan="99">
                    <table class="table table-shadow">
                        <thead class="thead-light">
                        <tr>
                            <th v-if="selectable" width="5.5%"></th>
                            <th v-for="title in child.params" :width="headersWidth">{{ getTitle(title) }}</th>
                            <slot name="header.extra-columns"></slot>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(children, index) in getChild(row, child.key)"
                            :data-id="children.id ? children.id : null">
                            <td v-if="selectable" class="text-center">
                                <av-checkbox @change="value => selectedHandler(value, index)" ref="rowChecks"/>
                            <td v-for="(param, key) in child.params">{{ get(children, key) }}</td>
                            <slot name="body.extra-columns"></slot>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </template>
    </component>
</template>

<script>
    import get from 'lodash/get'
    import has from 'lodash/has'
    import SimpleTable from './simple-table'

    export default {
        name: 'SimpleTableRow',

        components: {SimpleTable},

        props: {
            row: {
                type: Object,
                required: true
            },
            child: {
                type: Object,
                required: false
            },
            expandDefault: {
                type: Boolean,
                default: false
            },
            selectable: {
                type: Boolean,
                default: false
            }
        },

        data() {
            return {
                collapsed: !this.expandDefault,
                rowCheck: null
            }
        },

        computed: {
            isCollapsed() {
                return this.hasChildren ? this.collapsed : true;
            },

            hasKey() {
                return has(this.child, 'key');
            },

            hasChildren() {
                return !!this.getChildren.length;
            },

            getChildren() {
                return this.hasKey ? get(this.row, this.child.key) : [];
            },

            headersWidth() {
                const columnsCount = Object.keys(this.child.params).length
                return this.hasChildren ? `${(100 - 5.5) / columnsCount}%` : null
            },

            collapseIndicator() {
                return this.isCollapsed ? 'fa-chevron-right' : 'fa-chevron-down'
            }
        },

        methods: {
            toggleCollapsed() {
                this.collapsed = !this.collapsed;
            },

            selectedHandler(value, index) {
                this.$emit('selected', value, index)
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

            getChild(object, path) {
                path = path
                    .replace(/\[/g, '.')
                    .replace(/]/g, '')
                    .split('.');

                path.forEach(function (level) {
                    object = object[level];
                });

                return object === undefined ? null : object;
            },

            getTitle(item) {
                return item instanceof Object ? item.column : item
            },

            getWidth(item) {
                return item instanceof Object ? item.width : null
            },

            deselectRows() {
                this.$refs.rowChecks.forEach(item => item.deselect())
            }
        },
    }
</script>

<style scoped>
    .child {
        padding: 11px !important;
        cursor: pointer;
        background-color: #1f518f0a;
    }

    .expanded {
        display: contents;
    }

    .table-shadow {
        box-shadow: 0 2px 6px 0 rgba(0, 0, 0, 0.1)
    }

    .table-shadow tbody td {
        vertical-align: middle !important;
    }
</style>
