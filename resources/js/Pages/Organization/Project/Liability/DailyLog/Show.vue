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
                            <span class="action-text new-line"> / Daily Logs</span>
                        </v-col>

                        <v-col v-if="!onlyOneDiscipline" cols="12" lg="3">
                            <discipline-selector :project-disciplines="disciplines"/>
                        </v-col>
                    </v-row>

                </v-card-title>


                <div class="p-0 p-sm-4">
                    <v-row justify="center">
                        <v-col cols="12" lg="8">
                            <v-data-table
                                class="px-2"
                                hide-default-footer
                                disable-pagination
                                :search="search"
                                :headers="headersWithOffDayForDisciplineLead"
                                :items="logsForDiscipline"
                                :custom-sort="customSort"
                            >

                                <template v-slot:item.status="{ item }">
                                    <status-light
                                        :color="item.status === 'MISSING' ? 'red' : 'green'"
                                    />
                                </template>

                                <template v-slot:item.log_date="{item}">
                                    <v-btn
                                        class="text-capitalize font-weight-normal p-0"
                                        text
                                        tile
                                        :color="formatDateColor(item)"
                                        @click="openMessagingDialog(item)"
                                    >
                                        <span
                                            class="action-button-text"
                                            :class="item.status === 'MISSING' ? 'red--text' : (item.status === 'OFF_DAY' ? 'black--text' : '')"
                                        >{{ item.logDateWithDayOfWeek }}</span>
                                    </v-btn>
                                </template>

                                <template v-slot:item.wereIssues="{ item }">
                                    <span v-if="item.wereIssues !== null" :class="item.wereIssues ? 'red--text' : ''"> {{ item.wereIssues ? 'Yes' : 'No'}}</span>
                                </template>

                                <template v-slot:item.wereDelays="{ item }">
                                    <span v-if="item.wereDelays !== null" :class="item.wereDelays ? 'red--text' : ''">{{ item.wereDelays ? 'Yes' : 'No'}}</span>
                                </template>

                                <template v-slot:item.offDay="{ item }">
                                    <v-checkbox
                                        :input-value="item.status === 'OFF_DAY'"
                                        color="blue darken-5"
                                        class="text-center mx-auto w-25"
                                        :disabled="item.status !== 'MISSING' && item.status !== 'OFF_DAY'"
                                        @change="toggleOffDay(item)"
                                    ></v-checkbox>
                                </template>
                            </v-data-table>
                        </v-col>
                    </v-row>
                </div>

            </v-card>
        </v-col>

        <v-dialog v-model="messagingDialog" persistent>
            <view-daily-log
                v-if="selectedLog"
                :log="selectedLog"
                :discipline="selectedProjectDiscipline"
                @close="closeMessagingDialog"
            />
        </v-dialog>

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
                    Updating waived day...
                    <v-progress-linear
                        indeterminate
                        color="white"
                        class="mb-0"
                    ></v-progress-linear>
                </v-card-text>
            </v-card>
        </v-dialog>
    </v-row>

</template>

<script>
    import DisciplineSelector from "../../../../../Shared/DisciplineSelector";
    import InitializesDisciplineSelection from "../../../../../mixins/InitializesDisciplineSelection";
    import Layout from "../../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../../Shared/ProjectShowHeader";
    import {store} from "../../../../../project-store";
    import ViewDailyLog from "../../../../../Shared/Liability/ViewDailyLog";
    import StatusLight from "../../../../../Shared/StatusLight";

    export default {

        components: {
            StatusLight,
            DisciplineSelector,
            ViewDailyLog
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
            logs: Array
        },

        data: vm => {
            return {
                search: '',
                messagingDialog: false,
                selectedLog: null,
                loadingDialog: false,
                table: {
                    headers: [
                        {
                            text: '',
                            width: '15%',
                            value: 'status',
                            align: 'end',
                            sortable: false,
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Date',
                            width: '35%',
                            value: 'log_date',
                            class: 'subtitle-2 font-weight-bold'
                        },
                        {
                            text: 'Issues Reported',
                            width: '20%',
                            value: 'wereIssues',
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
            selectedProjectDiscipline() {
                return this.disciplines.find(projectDiscipline => projectDiscipline.id === this.selectedProjectDisciplineId);
            },
            logsForDiscipline() {
                return this.disciplines.find(discipline => discipline.id === this.selectedProjectDisciplineId).daily_logs.sort((date1, date2) => new Date(date2.log_date) - new Date(date1.log_date));
            },
            isUserLead() {
                return this.$page.auth.user.id === this.selectedProjectDiscipline.lead.id;
            },
            headersWithOffDayForDisciplineLead() {
                this.table.headers.splice(3, 1, {
                    text: 'Delays Reported',
                    width: '20%',
                    value: 'wereDelays',
                    divider: this.isUserLead,
                    class: 'subtitle-2 font-weight-bold'
                });

                return this.isUserLead
                    ? this.table.headers.concat({
                        text: 'Waive',
                        value: 'offDay',
                        width: '10%',
                        sortable: false,
                        align: 'center',
                        class: 'subtitle-2 font-weight-bold'
                    })
                    : this.table.headers;
            },
            onlyOneDiscipline() {
                return this.disciplines.length === 1;
            }
        },

        methods: {
            openMessagingDialog(item) {
                if (item.status === 'MISSING' || item.status === 'OFF_DAY') {
                    return;
                }

                this.selectedLog = this.logsForDiscipline.find(log => log.id === item.id);
                this.messagingDialog = true;
            },
            closeMessagingDialog() {
                this.selectedLog = null;
                this.messagingDialog = false;
            },
            formatDateColor(item) {
                return item.status === 'MISSING'
                    ? 'red'
                    : (item.status === 'OFF_DAY' ? 'black' : 'blue darken-5');
            },
            toggleOffDay(item) {
                this.loadingDialog = true;

                if (item.status !== 'OFF_DAY') {
                    this.$inertia.post(this.route('liability.daily-logs.off-day.store', [this.selectedProjectDisciplineId]), {
                        'off_date': item.log_date,
                    }, {
                        preserveState: false,
                        preserveScroll: true,
                    });
                } else {
                    this.$inertia.delete(this.route('liability.daily-logs.off-day.delete', [this.selectedProjectDisciplineId, item.off_day_id]), {
                        preserveState: false,
                        preserveScroll: true,
                    });
                }
            },
            customSort(items, index, isDescArr) {
                const [isDesc] = isDescArr;

                // a and b are objects representing the previous and next rows in the table
                // index is the 'value' property from headers on the active sort column
                items.sort((a, b) => {
                    // equal items sort equally
                    if (a[index] === b[index]) {
                        return 0;
                    }
                    // nulls sort after anything else
                    else if (a[index] === null) {
                        return 1;
                    } else if (b[index] === null) {
                        return -1;
                    }
                    // otherwise, if we're ascending, lowest sorts first
                    else if (!isDesc) {
                        return a[index] < b[index] ? -1 : 1;
                    }
                    // if descending, highest sorts first
                    else {
                        return a[index] < b[index] ? 1 : -1;
                    }
                });

                return items;
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
