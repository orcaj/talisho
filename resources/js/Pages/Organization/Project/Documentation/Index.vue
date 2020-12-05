<template>
    <v-row justify="center">
        <v-col class="mx-8">
            <v-card tile>
                <v-card-title class="blue darken-5 py-2">
                    <v-row justify="space-between" align="center" class="mx-2">

                        <v-col cols="12" lg="4">
                            <span class="pr-6 action-text">
                                Documentation
                            </span>
                        </v-col>

                        <v-col cols="12" lg="4">
                            <discipline-selector :project-disciplines="projectDisciplinesWithTeams"/>
                        </v-col>

                        <v-col cols="12" lg="2" offset-lg="2">
                            <v-row justify-lg="end">
                                <v-btn v-if="$page.auth.user.id === selectedDisciplineWithTeam.lead.id"
                                       color="orange actions"
                                       class="button-opacity ml-3 ml-lg-0"
                                       @click="dialog = true">
                                    <v-icon color="blue darken-5">mdi-plus</v-icon>
                                    <span class="action-button-text">
                                    Add New
                                </span>
                                </v-btn>
                            </v-row>
                        </v-col>
                    </v-row>

                </v-card-title>

                <div
                        class="p-4"
                        :class="selectedDisciplineWithTeam && !selectedDisciplineWithTeam.documentation_status ? 'disabled-area' : ''"
                >
                    <v-row justify="center">
                        <v-col cols="12" md="6">

                            <v-data-table
                                hide-default-footer
                                :headers="table.headers"
                                :items="dataForSelectedDisc"
                                disable-sort
                            >
                                <template v-slot:item.status="{ item }">
                                    <status-light
                                        :color="item.status.color"
                                    />
                                </template>

                                <template v-slot:item.title="{ item }">
                                    <inertia-link
                                        :href="route(`organizations.projects.documentation.show.${formatTitleForUrl(item.title)}`, [$page.organization.id, $page.project.id])"
                                        class="subtitle-1 no-underline"
                                    >
                                        <span class="action-button-text">{{ item.title === 'TAB/CX/LEED' ? 'TAB/Cx/LEED' : item.title }}{{item.title === 'General Requirement' || item.title === 'Submittal' ? 's' : ''}}
                                        </span>
                                    </inertia-link>
                                </template>

                            </v-data-table>
                        </v-col>
                    </v-row>
                </div>

            </v-card>
        </v-col>


        <!--  Create Document Dialog  -->

        <v-dialog v-model="dialog" persistent>
            <create-documentation
                v-if="dialog"
                @close="dialog = false"
                :project-discipline-with-team="selectedDisciplineWithTeam"
                :quick-lists="quickLists"
                :ids-of-csis-in-project="idsOfCSIsInProject"
                :submittal-divisions="submittalDivisions"
                :projectsList = "projectsList"
            >

            </create-documentation>
        </v-dialog>
    </v-row>
</template>

<script>
    import Layout from "../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../Shared/ProjectShowHeader";
    import CreateDocumentation from "./CreateDocumentation";
    import {store} from "../../../../project-store";
    import DisciplineSelector from "../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../mixins/InitializesDisciplineSelection";
    import StatusLight from "../../../../Shared/StatusLight";

    export default {
        components: {
            StatusLight,
            CreateDocumentation,
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
                        tab: 'documentation'
                    }
                }, [component]),
            ])
        },

        props: {
            projectDisciplinesWithTeams: {
                type: Array,
                required: true
            },
            quickLists: {
                type: Object,
                default: () => {
                }
            },
            projectsList: {
                type: Array,
            },
            idsOfCSIsInProject: {
                type: Array,
                default: () => []
            },
            submittalDivisions: {
                type: Array
            },
            info: {
                type: Object
            }
        },

        data() {
            return {
                dialog: false,
                table: {
                    headers: [
                        {
                            text: '',
                            width: '10%',
                            value: 'status',
                            sortable: false
                        },
                        {
                            text: '',
                            width: '60%',
                            value: 'title',
                            divider: true,
                            sortable: false
                        },
                        {
                            text: 'Documents Requested',
                            width: '30%',
                            value: 'count',
                            align: 'center',
                            sortable: false,
                            class: 'subtitle-1 pb-2 font-weight-bold'
                        }
                    ]
                }
            }
        },

        computed: {
            selectedDisciplineWithTeam() {
                return this.projectDisciplinesWithTeams.find(projectDiscipline => projectDiscipline.id === this.selectedProjectDisciplineId);
            },
            dataForSelectedDisc() {
                return this.selectedProjectDisciplineId ? this.info[this.selectedProjectDisciplineId][0] : this.info[Object.keys(this.info)[0]];
            },
            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            allProjectDisciplineIds() {
                return this.projectDisciplinesWithTeams.map(projectDiscipline => projectDiscipline.id);
            }
        },

        methods: {
            formatTitleForUrl(title) {
                const matchSpaceAndForwardSlash = /\s+|[\/]/g;
                return title.replace(matchSpaceAndForwardSlash, '-').toLowerCase();
            }
        },

        created() {
            this.initializeSelectDiscipline(this.allProjectDisciplineIds);
        }
    }
</script>
<style>
    .disabled-area{
        pointer-events: none;
    }
</style>
