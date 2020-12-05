<template>
    <v-col cols="12">
        <v-data-table
            :disable-pagination="true"
            :disable-sort="true"
            :hide-default-footer="true"
            :headers="headers"
            :items="tableData"
            @click:row="emitRowClick"
            class="transparent">
            <template v-slot:top>
                <slot name="header"></slot>
                <hr class="divider">
                <v-col cols="12" md="5" lg="3">
                    <v-text-field
                        v-model="search"
                        outlined
                        dense
                        clearable
                        label="Search"
                        append-icon="search"
                        @input="handleSearch"
                    >
                    </v-text-field>
                </v-col>
            </template>

            <!--    Default Slot for  templates    -->
            <template v-for="slot in itemSlots" :slot="slot" slot-scope="scope">
                <slot :name="slot" v-bind="scope"/>
            </template>

            <template v-slot:footer>
                <v-row class="py-2">
                    <v-col cols="6" md="2">
                        <v-subheader>Rows per page</v-subheader>
                    </v-col>

                    <v-col cols="6" md="2">
                        <v-select
                            v-model="pagination.per_page"
                            :items="[5, 10, 25]"
                            dense
                        ></v-select>
                    </v-col>


                    <v-spacer></v-spacer>

                    <v-col cols="10" md="5">
                        <v-pagination
                            v-model="pagination.page"
                            @input="fetchPaginatedData"
                            :length="totalPages"
                            color="primary darken-2"
                            :total-visible="7"
                        ></v-pagination>
                    </v-col>
                </v-row>
            </template>
        </v-data-table>
    </v-col>
</template>

<script>
    export default {
        name: "PaginatedTable",
        props: {
            tableData: {
                type: Array,
                required: true
            },
            headers: {
                type: Array,
                required: true
            },
            totalPages: {
                type: Number,
                required: true
            },
            options: {
                type: Object,
                default: () => {}
            }
        },
        data() {
            return {
                search: '',
                pagination: {
                    page: 1,
                    per_page: 5,
                },
            }
        },
        methods: {
            handleSearch: _.debounce(function () {
                this.pagination.page = 1;
                this.fetchPaginatedData();
            }, 300),
            async fetchPaginatedData() {
                this.$emit('table-change', {
                    page: this.pagination.page,
                    per_page: this.pagination.per_page,
                    search: this.search,
                });
            },
            emitRowClick(item) {
                this.$emit('row-click', item);
            }
        },
        computed: {
            itemSlots: function () {
                return Object.keys(this.$scopedSlots).filter(slot => slot.split('.').includes('item'));
            }
        },
        watch: {
            'pagination.per_page': function (newRowCount, oldRowCount) {
                if (newRowCount > oldRowCount) {
                    this.pagination.page = 1;
                }

                this.fetchPaginatedData();
            },
            'options': {
                handler() {
                    this.fetchPaginatedData();
                },
                deep: true
            }
        }
    }
</script>

<style scoped>
    .divider {
        border: none;
        height: 3px;
        color: #333;
        margin-bottom: 0.5rem;
        margin-top: 0;
        background-color: #333;
    }

    /* figure out table striping */
    .striped tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .05);
    }
</style>
