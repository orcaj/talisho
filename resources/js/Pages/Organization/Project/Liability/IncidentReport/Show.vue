<template>
    <v-row justify="center">
        <v-col class="mx-8">
            <v-card tile>
                <v-card-title class="blue darken-5 py-2">
                    <v-row align="center" justify="space-between" class="mx-2 my-0">

                        <v-col cols="12" :lg="onlyOneDiscipline ? 12 : 9">
                            <inertia-link
                                :href="route('organizations.projects.liability.index', [$page.organization.id, $page.project.id])"
                                class="action-text pr-1 no-underline"
                            >
                                <span class="action-text">
                                    Liability Protection
                                </span>
                            </inertia-link>
                            <span class="action-text new-line"> / Incident Reports</span>
                        </v-col>

                        <v-col v-if="!onlyOneDiscipline" cols="12" lg="3">
                            <discipline-selector :project-disciplines="disciplines"/>
                        </v-col>
                    </v-row>

                </v-card-title>


                <div class="p-4">
                    <v-row justify="center">
                        <v-col cols="12" lg="8">
                            <v-data-table
                                class="px-2"
                                hide-default-footer
                                disable-pagination
                                :search="search"
                                :headers="headersWithAcknowledgeForLead"
                                :items="reportsForDiscipline"
                            >
                                <template v-slot:item.status="{item}">
                                    <status-light
                                        :color="item.status.color"
                                    />
                                </template>

                                <template v-slot:item.incidentDateWithDayOfWeek="{item}">
                                    <v-btn
                                        class="text-capitalize font-weight-normal p-0"
                                        text tile color="blue"
                                        @click="openMessagingDialog(item.id)"
                                    >
                                        <span class="action-button-text">{{ item.incidentDateWithDayOfWeek }}</span>
                                    </v-btn>
                                </template>

                                <template v-slot:item.isInjuryReport="{item}">
                                    <span :class="item.isInjuryReport ? 'red--text' : ''">{{ item.isInjuryReport ? 'Yes' : 'No'}}</span>
                                </template>

                                <template v-slot:item.isIllnessReport="{item}">
                                    <span :class="item.isIllnessReport ? 'red--text' : ''">{{ item.isIllnessReport ? 'Yes' : 'No'}}</span>
                                </template>

                                <template v-slot:item.isNearMissReport="{item}">
                                    <span :class="item.isNearMissReport ? 'red--text' : ''">{{ item.isNearMissReport ? 'Yes' : 'No'}}</span>
                                </template>

                                <template v-slot:item.acknowledge="{item}">
                                    <v-checkbox
                                        :input-value="item.is_acknowledged"
                                        color="blue darken-5"
                                        class="text-center mx-auto w-25"
                                        @change="toggleAcknowledged(item)"
                                    />
                                </template>

                            </v-data-table>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-col>

        <!--     View Report Dialog      -->
        <v-dialog v-model="messagingDialog" persistent>
            <view-incident-report
                v-if="selectedReport"
                :incident-report="selectedReport"
                @close="closeMessagingDialog"
            />
        </v-dialog>
        <!--      End View Report Dialog     -->


        <!--          Loading Message       -->
        <v-dialog
            v-model="loadingDialog"
            persistent
            width="300"
        >
            <v-card
                color="blue darken-5"
                dark
            >
                <v-card-text>
                    Updating acknowledgement...
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
        <!--        End Loading Message       -->

    </v-row>
</template>

<script>
    import Layout from "../../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../../Shared/ProjectShowHeader";
    import DisciplineSelector from "../../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../../mixins/InitializesDisciplineSelection";
    import ViewIncidentReport from "../../../../../Shared/Liability/ViewIncidentReport";
    import {store} from "../../../../../project-store";
    import StatusLight from "../../../../../Shared/StatusLight";

    export default {
        components: {
            StatusLight,
            DisciplineSelector,
            ViewIncidentReport
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
                        tab: 'liability'
                    }
                }, [component]),
            ])
        },

        props: {
            disciplines: Array,
            reports: Array
        },

        data: vm => {
            return {
                search: '',
                loadingDialog: false,
                messagingDialog: false,
                selectedReport: null,
                table: {
                    headers: [
                        {
                            text: '',
                            width: '5%',
                            value: 'status',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Date',
                            width: '30%',
                            value: 'incidentDateWithDayOfWeek',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Injuries',
                            width: '15%',
                            value: 'isInjuryReport',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Illnesses',
                            width: '15%',
                            value: 'isIllnessReport',
                            class: 'subtitle-2 font-weight-bold'
                        },
                    ]
                }
            }
        },

        computed: {
            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            reportsForDiscipline() {
                return this.reports.filter(report => report.project_discipline_id === this.selectedProjectDisciplineId);
            },
            selectedProjectDiscipline() {
                return this.disciplines.find(disc => disc.id === this.selectedProjectDisciplineId);
            },
            isUserLead() {
                return this.$page.auth.user.id === this.selectedProjectDiscipline.lead.id;
            },
            onlyOneDiscipline() {
                return this.disciplines.length === 1;
            },
            headersWithAcknowledgeForLead() {
                this.table.headers.splice(4, 1, {
                    text: 'Near Misses',
                    width: '15%',
                    value: 'isNearMissReport',
                    divider: this.isUserLead,
                    class: 'subtitle-2 font-weight-bold'
                });

                return this.isUserLead ?
                    this.table.headers.concat({
                        text: 'Acknowledge',
                        width: '20%',
                        value: 'acknowledge',
                        align: 'center',
                        class: 'subtitle-2 font-weight-bold'
                    })
                    : this.table.headers;
            }
        },

        methods: {
            openMessagingDialog(id) {
                this.selectedReport = this.reports.find(report => report.id === id);
                this.messagingDialog = true;
            },
            closeMessagingDialog() {
                this.selectedReport = null;
                this.messagingDialog = false;
            },
            toggleAcknowledged(incidentReport) {
                this.loadingDialog = true;

                this.$inertia.put(this.route('liability.incident-report.update', [this.selectedProjectDisciplineId, incidentReport.id]), {
                    'is_acknowledged': !incidentReport.is_acknowledged
                }, {
                    preserveState: false,
                    preserveScroll: true,
                });
            }
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
