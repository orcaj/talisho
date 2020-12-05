<template>
    <v-row justify="center">
        <v-col class="mx-8">
            <v-card tile>
                <v-card-title class="blue darken-5 py-2">
                    <v-row justify="space-between" align="center" class="mx-2">

                        <v-col cols="12" lg="4">
                            <span class="pr-6 action-text">
                                Liability Protection
                            </span>
                        </v-col>

                        <v-col cols="12" lg="4">
                            <discipline-selector :project-disciplines="projectDisciplines"/>
                        </v-col>

                        <v-col cols="12" lg="2" offset-lg="2">
                            <v-row justify-lg="end">
                                <v-btn v-if="userInTeam" @click="reportSelectDialog = true"
                                       color="orange actions"
                                       class="button-opacity ml-3 ml-lg-0"
                                >
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
                        :class="selectedDiscipline && !selectedDiscipline.liability_status ? 'disabled-area' : ''"
                >
                    <v-row justify="center">
                        <v-col cols="6">
                            <v-data-table
                                hide-default-footer
                                :headers="table.headers"
                                :items="tableDataForSelectedDiscipline"
                            >
                                <template v-slot:item.status="{ item }">
                                    <status-light
                                        :color="item.status.color"
                                    />
                                </template>

                                <template v-slot:item.title="{ item }">
                                    <payment-required-tooltip
                                        :full-access="!!$page.project.full_access"
                                        v-slot="{ on }"
                                    >
                                        <span v-on="on">
                                            <v-btn
                                                text
                                                tile
                                                :disabled="! $page.project.full_access"
                                                @click="handleRedirectToShow(item)"
                                                class="subtitle-1 no-underline text-capitalize"
                                            >
                                                <span class="action-button-text">{{ item.title }}</span>
                                            </v-btn>
                                        </span>
                                    </payment-required-tooltip>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </div>
            </v-card>
        </v-col>

        <!--   Form Type Selection Modal     -->
        <report-selection-modal
            v-if="reportSelectDialog"
            :dialog="reportSelectDialog"
            @select="handleFormSelection"
            @close="reportSelectDialog = false"
        />
        <!--   End Form Type Selection Modal     -->


        <v-dialog v-model="dialog" persistent>
            <create-incident-report
                v-if="dialog && formType === 'incident-report'"
                :discipline="selectedDiscipline"
                @close="dialog = false"
            />
            <create-daily-log
                v-if="dialog && formType === 'daily-log'"
                :discipline="selectedDiscipline"
                @close="dialog = false"
            />
        </v-dialog>

    </v-row>
</template>

<script>
    import Layout from "../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../Shared/ProjectShowHeader";
    import DisciplineSelector from "../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../mixins/InitializesDisciplineSelection";
    import {store} from "../../../../project-store";
    import CreateIncidentReport from "../../../../Shared/Liability/CreateIncidentReport";
    import ReportSelectionModal from "../../../../Shared/Liability/ReportSelectionModal";
    import CreateDailyLog from "../../../../Shared/Liability/CreateDailyLog";
    import StatusLight from "../../../../Shared/StatusLight";
    import PaymentRequiredTooltip from "../../../../Shared/PaymentRequiredTooltip";

    export default {
        components: {
            StatusLight,
            DisciplineSelector,
            CreateIncidentReport,
            ReportSelectionModal,
            CreateDailyLog,
            PaymentRequiredTooltip
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
            projectDisciplines: {
                type: Array,
                required: true
            },
            tableData: {
                type: Object
            }
        },

        data() {
            return {
                dialog: false,
                reportSelectDialog: false,
                formType: null,
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
                            text: 'Total Submitted',
                            width: '30%',
                            value: 'quantity',
                            align: 'center',
                            class: 'subtitle-1 pb-2 font-weight-bold',
                            sortable: false
                        }
                    ]
                }
            }
        },

        computed: {
            teamMemberIds() {
                return this.selectedDiscipline.team.map(({id}) => id);
            },
            projectDisciplineIds() {
                return this.projectDisciplines.map(({id}) => id);
            },
            selectedDiscipline() {
                return this.projectDisciplines.find(({id}) => id === this.selectedProjectDisciplineId);
            },
            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            userInTeam() {
                return this.teamMemberIds.includes(this.$page.auth.user.id)
            },
            userLeadsTeam() {
                return this.selectedDiscipline.lead.id === this.$page.auth.user.id;
            },
            tableDataForSelectedDiscipline() {
                return this.tableData[this.selectedProjectDisciplineId][0];
            }
        },

        methods: {
            closeDialog() {
                this.dialog = false;
            },
            formatTitleForUrl(title) {
                const matchSpaceAndForwardSlash = /\s+|[\/]/g;
                const matchApostraphe = /[^\w ]/g;
                return title.replace(matchApostraphe, '').replace(matchSpaceAndForwardSlash, '-').toLowerCase();
            },
            handleFormSelection(form) {
                this.formType = form;
                this.reportSelectDialog = false;
                this.dialog = true;
            },
            handleRedirectToShow(item) {
                this.$inertia.visit(
                    this.route(`organizations.projects.liability.${this.formatTitleForUrl(item.title)}`, [this.$page.organization.id, this.$page.project.id])
                );
            }
        },

        created() {
            this.initializeSelectDiscipline(this.projectDisciplineIds);
        }
    }
</script>
<style>
    .disabled-area{
        pointer-events: none;
    }
</style>
