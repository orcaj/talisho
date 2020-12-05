<template>
    <v-container>
        <organization-header
                :organization="organization"
                :company="company"
        >
            <inertia-link v-if="$page.auth.user.can.create.projects"
                          class="no-underline"
                          :href="route('organizations.projects.create', {organization: organization.id})"
                          id="new-project">
                <v-btn color="blue darken-5" class="p-1 pr-2 white--text" title="Add Project">
                    <v-icon color="white">mdi-plus</v-icon>
                    New Project
                </v-btn>
            </inertia-link>
        </organization-header>

        <v-row justify="center" align="center">
            <v-col cols="12">

                <v-data-table
                        ref="projects"
                        disable-pagination
                        disable-filtering
                        hide-default-footer
                        class="transparent"
                        @update:sort-desc="setSort"
                        :show-expand="true"
                        :headers="headers"
                        :items="projects"
                >

                    <template v-slot:header.status>
                        <v-tooltip v-if="$page.auth.user.can.view.disciplines" content-class="summary-tooltip" bottom>
                            <template v-slot:activator="{ on }">
                                <v-icon v-on="on">mdi-information-outline</v-icon>
                            </template>
                            <status-light-summary-tooltip />
                        </v-tooltip>
                    </template>

                    <template v-slot:top>
                        <v-toolbar class="transparent" flat color="white">
                            <v-row align="center">
                                <v-tabs
                                        :value="tab"
                                        :slider-size="6"
                                        color="blue darken-5"
                                        class="tab-class"
                                        background-color="transparent"
                                >
                                    <v-tab class="no-underline mx-2 py-6"
                                           @click="selectTab('manager')"
                                    >
                                        <status-light
                                                class="mr-3"
                                                :color="setColor(mprojects)"
                                        />
                                        <span class="subtitle-1"
                                              :class="tab === 'manager' ? 'black--text font-weight-bold' : 'grey--text'"
                                        >
                                    My Company Projects ({{ mcount }})

                                </span>
                                    </v-tab>

                                    <v-tab class="no-underline mx-2 py-6"
                                           @click="selectTab('guest')"
                                    >
                                        <status-light
                                                class="mr-3"
                                                :color="setColor(gprojects)"
                                        />
                                        <span class="subtitle-1"
                                              :class="tab === 'guest' ? 'black--text font-weight-bold' : 'grey--text'"
                                        >
                                    My Guest Projects ({{ gcount }})
                                    </span>
                                    </v-tab>

                                </v-tabs>

                            </v-row>
                        </v-toolbar>
                        <hr class="divider">
                    </template>

                    <template v-slot:item.status="{item}">
                        <v-tooltip content-class="summary-tooltip" bottom>
                            <template v-slot:activator="{ on }">
                                <status-light
                                        :should-show-tooltip="$page.auth.user.can.view.disciplines || $page.auth.user.id === item.disciplines[0].lead.id"
                                        :on-hover="on"
                                        :color="moreThanOneDiscipline(item) ? item.status.color : item.disciplines[0].status.color"
                                />
                            </template>
                            <project-summary-tooltip
                                    :summary-data="$page.auth.user.can.view.disciplines || moreThanOneDiscipline(item) ? item.summaryData : item.disciplines[0].summaryData"
                            />
                        </v-tooltip>
                    </template>

                    <template v-slot:item.name="{ item }">
                        <span @click="showProjectSummary(item)" class="clickable">{{item.name}}</span>
                    </template>

                    <template v-slot:item.created_at="{item}">
                        {{ formatDate(item.created_at)}}
                    </template>

                    <template v-slot:item.notifications="{item}">
                        <notifications-dropdown :base-notifications="item.notifications" :project-id="item.id" />
                    </template>

                    <template v-slot:expanded-item="{ headers, item }">
                        <td :colspan="headers.length" class="px-0 mx-0">

                            <v-data-table
                                    class="transparent"
                                    hide-default-footer
                                    hide-default-header
                                    disable-filtering
                                    disable-sort
                                    disable-pagination
                                    :items="item.disciplines"
                                    :headers="[
                                    {
                                        text: '',
                                        value: '',
                                    },
                                    {
                                        value: 'discipline',
                                    }

                                ]"
                            >
                                <template v-slot:item.discipline="{item}">
                                    <v-row class="pl-12 pr-8">
                                        <v-col cols="2">
                                            <v-tooltip
                                                    content-class="summary-tooltip"
                                                    bottom
                                                    :disabled="!$page.auth.user.can.view.disciplines && item.lead.id !== $page.auth.user.id"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <status-light
                                                            class="mr-2"
                                                            :on-hover="on"
                                                            :color="item.status.color"
                                                            :should-show-tooltip="$page.auth.user.can.view.disciplines || $page.auth.user.id === item.lead.id"
                                                    />
                                                </template>

                                                <project-summary-tooltip
                                                        :summary-data="item.summaryData"
                                                />
                                            </v-tooltip>
                                            <span @click="showProjectSummary(item.project, item.id)" class="clickable">
                                                {{item.discipline.label}}
                                            </span>
                                        </v-col>
                                        <v-col offset="1" cols="2">
                                            {{item.lead.name}}
                                        </v-col>
                                        <v-col offset="1" cols="2" class="text-right">
                                            <div v-if="$page.auth.user.can.view.disciplines || $page.auth.user.id === item.lead.id">
                                                Documentation:
                                                <span
                                                        v-if="item.summaryData.documentation_status == 1"
                                                        class="switch-on-text"
                                                >
                                                 &nbsp;ON
                                            </span>
                                                <span
                                                        v-if="item.summaryData.documentation_status == 0"
                                                        class="switch-off-text"
                                                >
                                                 OFF
                                            </span>
                                            </div>
                                        </v-col>
                                        <v-col  cols="2" class="text-right pl-1">
                                            <div v-if="$page.auth.user.can.view.disciplines || $page.auth.user.id === item.lead.id">
                                                Communication:
                                                <span
                                                        v-if="item.summaryData.communication_status == 1"
                                                        class="switch-on-text"
                                                >
                                                 &nbsp;ON
                                            </span>
                                                <span
                                                        v-if="item.summaryData.communication_status == 0"
                                                        class="switch-off-text"
                                                >
                                                 OFF
                                            </span>
                                            </div>
                                        </v-col>
                                        <v-col  cols="2" class="text-right pl-1">
                                            <div v-if="$page.auth.user.can.view.disciplines || $page.auth.user.id === item.lead.id">
                                                Liability Protection:
                                                <span
                                                        v-if="item.summaryData.liability_status == 1"
                                                        class="switch-on-text"
                                                >
                                                 &nbsp;ON
                                            </span>
                                                <span
                                                        v-if="item.summaryData.liability_status == 0"
                                                        class="switch-off-text"
                                                >
                                                 OFF
                                            </span>
                                            </div>
                                        </v-col>

                                    </v-row>
                                </template>
                            </v-data-table>
                        </td>
                    </template>

                </v-data-table>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import Layout from "../../../Shared/Layout";
    import PaginatedTable from "../../../Shared/PaginatedTable";
    import OrganizationHeader from "../../../Shared/OrganizationHeader";
    import InteractsWithDates from "../../../mixins/InteractsWithDates";
    import ProjectSummaryTooltip from "../../../Shared/ProjectSummaryTooltip";
    import { mutations } from "../../../project-store";
    import StatusLight from "../../../Shared/StatusLight";
    import NotificationsDropdown from "../../../Shared/NotificationsDropdown";
    import StatusLightSummaryTooltip from "../../../Shared/StatusLightSummaryTooltip";

    export default {
        name: "ProjectLanding",
        components: {
            StatusLightSummaryTooltip,
            StatusLight,
            PaginatedTable,
            OrganizationHeader,
            ProjectSummaryTooltip,
            NotificationsDropdown
        },
        mixins: [
            InteractsWithDates
        ],
        layout: Layout,
        props: {
            mcount: {
                type: Number,
                required: true
            },
            gcount: {
                type: Number,
                required: true
            },
            organization: {
                type: Object,
                required: true
            },

            company: {
                type: Object,
                required: true
            },
            mprojects: {
                type: Array,
                required: true
            },
            gprojects: {
                type: Array,
                required: true
            }
        },
        data(vm) {
            return {
                tab: this.$page.auth.user.can.view.disciplines ? 'manager': 'guest',
                projects: this.$page.auth.user.can.view.disciplines ? this.mprojects: this.gprojects ,
                headers: [
                    {
                        text: '',
                        value: 'status',
                        width: '5%',
                        sort: (a, b) => {
                            // white status is priority 4 sort after anything else

                            if (vm.sortDesc) {
                                if (a.priority === 4) {
                                    return -1;
                                }

                                if (b.priority === 4) {
                                    return 1;
                                }

                            }

                            return a.priority - b.priority;
                        }
                    },
                    {
                        text: "Project Name",
                        value: 'name',
                        divider: true,
                        width: '37%',
                        class: 'font-weight-bold body-1'
                    },
                    {
                        text: "Client Name",
                        value: 'client_name',
                        divider: true,
                        class: 'font-weight-bold body-1',
                        width: '26%'
                    },
                    {
                        text: "City",
                        value: 'city',
                        divider: true,
                        class: 'font-weight-bold body-1',
                        width: '11%'
                    },
                    {
                        text: "State",
                        value: 'state',
                        divider: true,
                        class: 'font-weight-bold body-1',
                        width: '6%'
                    },
                    {
                        text: 'Date Created',
                        value: 'created_at',
                        divider: true,
                        class: 'font-weight-bold body-1',
                        width: '10%'
                    },
                    {
                        text: '',
                        value: 'notifications',
                        divider: true,
                        sortable: vm.$page.auth.user.can.view.disciplines,
                        width: '5%'
                    }
                ],
                sortDesc: null
            }
        },

        methods: {
            showProjectSummary(project, disciplineId = null) {
                if (!!disciplineId) {
                    mutations.setSelectedProjectDisciplineId(disciplineId);
                }

                this.$inertia.visit(this.route('organizations.projects.documentation.index', {
                    organization: project.organization_id,
                    project: project.id
                }));
            },
            moreThanOneDiscipline(project) {
                return project.disciplines.length > 1;
            },

            setColor(selectedProjects){
                var colors = {
                    'white': 4,
                    'green': 3,
                    'yellow': 2,
                    'red': 1
                };
                var status_color  = 'white';
                selectedProjects.forEach(function(project) {
                    if(colors[project.status.color] < colors[status_color]){
                        status_color = project.status.color
                    }
                });
                return status_color;

            },
            setSort(e) {
                this.sortDesc = e[0];
            },
            selectTab(selector){
                this.tab = selector;
                if (this.tab == "manager"){
                    this.projects = this.mprojects;
                }
                else if(this.tab == 'guest'){
                    this.projects = this.gprojects;
                }
            }
        },
    }
</script>
<style>
    .tab-class .v-tabs-slider-wrapper{
        display: none!important;
    }
</style>
<style scoped>
    .checkbox-label {
        margin-bottom: 0;
    }

    .divider {
        border: none;
        height: 3px;
        color: #333;
        margin-bottom: 0.5rem;
        margin-top: 0;
        background-color: #333;
    }

    #new-project:hover {
        text-decoration: none;
    }

    .summary-tooltip {
        background-color: #F5F5F5;
        color: black;
        border-radius: 10px;
        border: black 2px solid;
        opacity: 1 !important;
    }
    .switch-on-text{
        color: green!important;
        font-weight: 900;
    }

    .switch-off-text{
        color: red!important;
        font-weight: 900;
    }

    .subtitle-1{
        font-weight: 900!important;
    }

</style>
