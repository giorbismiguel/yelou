<template>
    <div class="btn-group dropleft fg">
        <div data-toggle="dropdown" class="dropdown-link" @click="clickHandler"
             @mouseenter="mouseEnter" v-show="visible">
            <i class="far fa-ellipsis-v"></i>
        </div>
        <div class="dropdown-menu">
            <slot></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'Actions',

        props: {
            afterCreated: {
                type: Function,
                default: () => {
                }
            },

            rowData: {
                type: Object,
                default: () => {
                }
            }
        },

        data() {
            return {
                visible: true
            }
        },

        watch: {},

        methods: {
            clickHandler($event) {
                this.$emit('opened', $event, this)
            },

            mouseEnter($event) {
                this.$emit('entered', $event, this)
            },

            hide() {
                this.visible = false
            }
        },

        mounted() {
            $(this.$el).on('hide.bs.dropdown', () => this.$emit('close'))
            $(this.$el).on('show.bs.dropdown', ($event) => this.$emit('show', $event))
        },
    }
</script>

<style scoped>
    .dropdown-link {
        transition: background-color 150ms cubic-bezier(0.4, 0, 0.2, 1) 0ms;
        border-radius: 50%;
        height: 35px;
        width: 35px;
        flex: 0 0 auto;
        text-align: center;
        cursor: pointer;
        margin: 2px;
    }

    .dropdown-link:hover {
        background-color: rgba(0, 0, 0, 0.08);
    }

    .dropdown-link i {
        display: flex;
        padding-top: 12px;
        justify-content: center;
    }
</style>
