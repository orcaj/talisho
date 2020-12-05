<template>
    <v-row justify="center">
        <v-col class="mx-8">
            <v-card tile>
                <v-card-title class="blue darken-5 py-2">
                    <v-row align="center" justify="space-between" class="mx-2 my-0">

                        <v-col cols="12" lg="9">

                            <inertia-link
                                :href="route('organizations.projects.communication.index', [$page.organization.id, $page.project.id])"
                                class="pr-1 action-text no-underline"
                            >
                                <span class="action-text">
                                    Communication
                                </span>
                            </inertia-link>
                            <span class="action-text new-line"> / Construction </span> <span class="action-text new-line"> Directives</span>
                        </v-col>

                        <v-col v-if="!onlyOneDiscipline" cols="12" lg="3">
                            <discipline-selector :project-disciplines="disciplines"/>
                        </v-col>
                    </v-row>

                </v-card-title>

                <div class="py-4">
                    <v-row justify="center">
                        <v-col cols="12" lg="7">
                            <v-data-table
                                class="px-2"
                                hide-default-footer
                                disable-pagination
                                :search="search"
                                :headers="table.headers"
                                :items="communicationsForSelectedDiscipline"
                            >
                                <template v-slot:item.identifier="{item}">
                                    <span>
                                        CD-{{ item.identifier }}
                                    </span>
                                </template>

                                <template v-slot:item.subject="{item}">
                                    <v-btn
                                        class="text-capitalize font-weight-normal p-0"
                                        text tile color="blue"
                                        @click="openMessagingDialog(item.id)"
                                    >
                                        <span class="action-button-text">{{ item.subject }}</span>
                                    </v-btn>
                                </template>

                                <template v-slot:item.date="{ item }">
                                    <span>{{ item.date }}</span>
                                </template>
                            </v-data-table>

                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-col>

        <!--       Messaging Dialog        -->
        <v-dialog v-model="openMessaging" persistent>
            <construction-directive-container
                v-if="selectedCommunication"
                :directive="selectedCommunication"
                @close="closeMessagingDialog"
            />
        </v-dialog>
        <!--     End Messaging Dialog     -->

    </v-row>
</template>

<script>
    import Layout from "../../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../../Shared/ProjectShowHeader";
    import RfiMessagingContainer from "../../../../../Shared/Communication/RfiMessagingContainer";
    import ConstructionDirectiveContainer from "../../../../../Shared/Communication/ConstructionDirectiveContainer";
    import DisciplineSelector from "../../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../../mixins/InitializesDisciplineSelection";
    import {store} from "../../../../../project-store";

    export default {
        components: {
            ConstructionDirectiveContainer,
            DisciplineSelector
        },

        mixins: [
            InitializesDisciplineSelection
        ],

        layout: (h, component) => {
            const {organization, project} = component.data.props;
            return h(Layout, [
                h(ProjectShowHeader, {
                    props: {
                        organization,
                        project,
                        tab: 'communication'
                    }
                }, [component]),
            ])
        },

        props: {
            communications: {
                type: Array
            },
            disciplines: {
                type: Array
            },
        },

        data() {
            return {
                openMessaging: false,
                table: {
                    headers: [
                        {
                            text: 'Directive No.',
                            width: '25%',
                            value: 'identifier',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Subject',
                            width: '55%',
                            value: 'subject',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Date Issued',
                            width: '20%',
                            value: 'date',
                            class: 'subtitle-2 font-weight-bold'
                        }
                    ]
                },
                selectedCommunication: null,
                search: ''
            }
        },

        computed: {
            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            selectedDiscipline() {
                return this.disciplines.find(({id}) => id === this.selectedProjectDisciplineId);
            },
            communicationsForSelectedDiscipline() {
                return this.communications.filter(communication => communication.project_discipline_id === this.selectedProjectDisciplineId);
            },
            onlyOneDiscipline() {
                return this.disciplines.length === 1;
            }
        },

        methods: {
            async openMessagingDialog(id) {
                this.selectedCommunication = await this.getCommunicationData(id);
                this.openMessaging = true;
            },
            closeMessagingDialog() {
                this.selectedCommunication = null;
                this.openMessaging = false;
            },
            async getCommunicationData(id) {
                const {data} = await window.axios.get(this.route(`cd.ajax`, [id]));
                return data;
            },
        },

        created() {
            this.initializeSelectDiscipline(this.disciplines.map(({id}) => id));
        }
    }
</script>

<style scoped>
    .new-line {
        display: inline-block;
    }
</style>
