<template>
    <Layout>
        <Header :project="project">
            <discipline-selector :project-disciplines="projectDisciplines" />
        </Header>
        <v-row class="mt-2" justify="center">
            <v-col cols="10" class="pb-0">
                <h6 class="font-weight-bold text-capitalize">{{ title }}</h6>
                <hr class="line">
            </v-col>
        </v-row>
        <v-row justify="center">
            <v-col cols="10" class="pt-0">
                <template v-for="item in tableDataForSelectedDiscipline">
                    <div>
                        <a v-if="item.approved" target="_blank" class="underline file-text" :href="route('download-file', [item.file.id])">{{ renderDisplayName(item) }}</a>
                        <span class="file-text" v-else-if="item.isForSubmittalOrCommissioning">{{ renderDisplayName(item) }}</span>
                    </div>
                </template>
            </v-col>
        </v-row>
    </Layout>
</template>

<script>
    import DisciplineSelector from "../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../mixins/InitializesDisciplineSelection";
    import {store} from "../../../../project-store";
    import Layout from "../../../../Shared/Layout";
    import Header from "./Header";

    export default {
        components: {
            Header,
            Layout,
            DisciplineSelector,
        },

        mixins: [
            InitializesDisciplineSelection
        ],

        props: {
            projectDisciplines: {
                type: Array,
                required: true
            },
            tableData: {
                type: Object,
                required: true
            },
            project: {
                type: Object,
                required: true
            },
            title: {
                type: String,
                required: true
            }
        },

        data() {
            return {
                dialog: false,
                reportSelectDialog: false,
            }
        },

        computed: {
            projectDisciplineIds() {
                return this.projectDisciplines.map(({id}) => id);
            },

            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            tableDataForSelectedDiscipline() {
                return this.tableData[this.selectedProjectDisciplineId][0];
            }
        },

        methods: {
            closeDialog() {
                this.dialog = false;
            },
            renderDisplayName(item) {
                if (!!item.submittal) {
                    return item.submittal.specification.name;
                }

                if (!item.isForSubmittalOrCommissioning) {
                    return item.file.name;
                }

                return item.specification.name;
            }
        },

        created() {
            this.initializeSelectDiscipline(this.projectDisciplineIds);
        }
    }
</script>

<style scoped>
    .underline {
        text-decoration: underline;
    }

    .file-text {
        line-height: 100%;
        font-size: 0.875rem;
    }

    .line {
        margin-top: 5px;
        background-color: black;
        height: 2px;
    }
</style>
