<template>
    <div class="background fill-height">
        <v-row justify="center">
            <v-col class="pt-0">
                <!--   Project Info Dialog       -->
                <v-dialog
                    v-model="info"
                    width="500"
                >
                    <v-card>
                        <v-card-title
                            class="headline grey lighten-2"
                            primary-title
                        >
                            {{ project.name }}
                        </v-card-title>

                        <v-card-text class="mt-2">
                            {{ project.description }}
                        </v-card-text>

                        <v-divider></v-divider>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                color="blue darken-5"
                                text
                                @click="toggleShowProjectInfo"
                            >
                                Close
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!--   End Project Info Dialog     -->

                <!--   Switch Confirm  Dialog       -->
                <v-dialog
                        v-model="switchConfirm"
                        max-width="290"
                >
                    <v-card>
                        <v-card-title class="headline">
                        </v-card-title>
                        <v-card-text>Are you sure to turn off?</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn
                                    color="green darken-1"
                                    text
                                    @click="toggleSwitchConfirmNo"
                            >
                                No
                            </v-btn>
                            <v-btn
                                    color="red darken-1"
                                    text
                                    @click="toggleSwitchConfirmYes"
                            >
                                Yes
                            </v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>

                <!--   End  Switch Confirm  Dialog     -->

                <v-row class="px-12 pt-4 blue darken-5">
                    <v-col cols="12">
                        <back-to-projects-button
                            :click-handler="back"
                        />
                    </v-col>
                </v-row>

                <v-row class="px-md-12 pb-md-8 blue darken-5" justify="center" align-content="space-around">
                    <v-col>
                        <v-row justify="center" class="ml-lg-12">
                            <h1 class="font-weight-bold header-text ml-4">
                                {{ project.name }}
                            </h1>

                            <v-btn
                                class="ml-4 px-2"
                                min-width="0"
                                icon
                                @click="toggleShowProjectInfo"
                                :ripple="false"
                                title="Show Project Information"
                            >
                                <v-icon color="orange actions">mdi-information</v-icon>
                            </v-btn>
                            <v-btn
                                v-if="$page.auth.user.can.edit.projects"
                                class="mx-0 px-2"
                                min-width="0"
                                icon
                                @click="editProject"
                                :ripple="false"
                                title="Edit Project Information"
                            >
                                <v-icon :ripple="false" color="orange actions">mdi-settings</v-icon>
                            </v-btn>
                        </v-row>

                        <v-row justify="center" class="mt-lg-n4">
                            <v-subheader class="white--text mt-sm-n4 mt-lg-0">{{ project.client_name }}</v-subheader>
                            <v-divider class="white" vertical></v-divider>
                            <v-subheader class="white--text mt-sm-n4 mt-lg-0"> {{ project.address_1 }}, {{ project.city }}, {{
                                project.state}}, {{
                                project.zip}}
                            </v-subheader>
                        </v-row>

                    </v-col>
                </v-row>

                <v-row
                        class="blue darken-5 px-2"
                        justify="space-between"
                        v-if = "selectedDisciplineWithTeam && selectedDisciplineWithTeam.lead && (($page.auth.user.id === selectedDisciplineWithTeam.lead.id) || $page.auth.user.can.edit.projects)"
                >

                    <v-col class="switch-tabs-row">
                        <v-row class="ml-lg-12 switch-tabs">
                            <div class="switch-tab-1">
                                <v-switch
                                        v-model="selectedDisciplineWithTeam && selectedDisciplineWithTeam.documentation_status"
                                        :false-value="0"
                                        :true-value="1"
                                        color="green"
                                        @change="toggleProjectSwitch('documentation')"
                                >
                                </v-switch>
                            </div>
                            <div class="switch-tab-2">
                                <v-switch
                                        v-model="selectedDisciplineWithTeam &&  selectedDisciplineWithTeam.communication_status"
                                        :false-value="0"
                                        :true-value="1"
                                        color="green"
                                        @change="toggleProjectSwitch('communication')"
                                >
                                </v-switch>
                            </div>
                            <div class="switch-tab-3">
                                <v-switch
                                        v-model="selectedDisciplineWithTeam &&  selectedDisciplineWithTeam.liability_status"
                                        :false-value="0"
                                        :true-value="1"
                                        color="green"
                                        @change="toggleProjectSwitch('liability')"
                                >
                                </v-switch>
                            </div>
                        </v-row>
                    </v-col>
                </v-row>

                <v-row class="tab-background px-12" justify="space-between">
                    <v-tabs
                        :value="tab"
                        :slider-size="6"
                        color="blue darken-5"
                        background-color="transparent"
                    >
                        <v-tab class="no-underline mx-2 py-6"
                               @click.prevent="visit('organizations.projects.documentation.index')"
                               :disabled = "selectedDisciplineWithTeam && !selectedDisciplineWithTeam.documentation_status"
                               href="documentation">
                            <status-light
                                v-if="selectedProjectDisciplineId"
                                class="mr-2"
                                :color="project.tabStatuses.documentation[selectedProjectDisciplineId][0].color"
                            />
                            <span class="subtitle-1"
                                  :class="tab === 'documentation' ? 'black--text font-weight-bold' : 'black--text'"
                            >
                                    DOCUMENTATION
                                </span>
                        </v-tab>

                        <v-tab class="no-underline mx-2 py-6"
                               @click.prevent="visit('organizations.projects.communication.index')"
                               :disabled = "selectedDisciplineWithTeam && !selectedDisciplineWithTeam.communication_status"
                               href="communication">
                            <status-light
                                v-if="selectedProjectDisciplineId"
                                class="mr-2"
                                :color="project.tabStatuses.communication[selectedProjectDisciplineId][0].color"
                            />
                            <span class="subtitle-1"
                                  :class="tab === 'communication' ? 'black--text font-weight-bold' : 'black--text'"
                            >
                                    COMMUNICATION
                                </span>
                        </v-tab>

                        <v-tab class="no-underline mx-2 py-6"
                               @click.prevent="visit('organizations.projects.liability.index')"
                               :disabled = "selectedDisciplineWithTeam && !selectedDisciplineWithTeam.liability_status"
                               href="liability">
                            <status-light
                                v-if="selectedProjectDisciplineId"
                                class="mr-2"
                                :color="project.tabStatuses.liability[selectedProjectDisciplineId][0].color"
                            />
                            <span class="subtitle-1"
                                  :class="tab === 'liability' ? 'black--text font-weight-bold' : 'black--text'"
                            >
                                    LIABILITY PROTECTION
                                </span>
                        </v-tab>

                        <v-spacer></v-spacer>

                        <v-tab class="no-underline py-6 mx-2" @click.prevent="visit('organizations.projects.team.index')"
                               href="team">
                                <span class="subtitle-1"
                                      :class="tab === 'team' ? 'black--text font-weight-bold' : 'black--text'"
                                >
                                    TEAM
                                </span>
                        </v-tab>

                        <v-tab class="no-underline py-6 mx-2" @click.prevent="visit('organizations.projects.design-documents.index')"
                                href="design-documents">
                                <span class="subtitle-1"
                                      :class="tab === 'design-documents' ? 'black--text font-weight-bold' : 'black--text'"
                                >
                                    DESIGN DOCUMENTS
                                </span>
                        </v-tab>
                    </v-tabs>

                </v-row>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12" class="position-relative fill-height">
                <slot></slot>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import {store, mutations} from '../project-store';
    import StatusLight from "./StatusLight";
    import BackToProjectsButton from "./BackToProjectsButton";

    export default {
        name: "Show.vue",
        components: {
            StatusLight,
            BackToProjectsButton
        },

        data() {
            return {
                info: false,
                switchConfirm: false
            }
        },

        props: ['organization', 'project', 'tab', 'company'],


        computed: {
            selectedProjectDisciplineId() {
                if (store.selectedProjectDisciplineId)
                    return store.selectedProjectDisciplineId;
                else
                    return this.project.disciplines[0].id;
            },

            selectedDisciplineWithTeam() {
                return this.project.disciplines.find(projectDiscipline => projectDiscipline.id === this.selectedProjectDisciplineId);
            }
        },

        methods: {
            back() {
                this.$inertia.visit(this.route('home'));
            },
            editProject() {
                this.$inertia.visit(this.route('organizations.projects.edit', {
                    organization: this.organization.id,
                    project: this.project.id
                }));
            },
            toggleShowProjectInfo() {
                this.info = !this.info;
            },
            toggleProjectSwitch(type){
                if(type == "documentation" && this.selectedDisciplineWithTeam.documentation_status == 0){
                    this.toggleSwitchConfirmation();
                    this.switchType  = type
                }
                else if(type  == "communication" && this.selectedDisciplineWithTeam.communication_status == 0){
                    this.toggleSwitchConfirmation();
                    this.switchType  = type
                }
                else if(type  == "liability" && this.selectedDisciplineWithTeam.liability_status == 0){
                    this.toggleSwitchConfirmation();
                    this.switchType  = type
                }
                else{
                    this.submit();
                }
            },
            submit() {
                this.$inertia.put(this.route('project-discipline.change-switch', [this.selectedDisciplineWithTeam.id]), {
                    'documentation_status': this.selectedDisciplineWithTeam.documentation_status,
                    'communication_status': this.selectedDisciplineWithTeam.communication_status,
                    'liability_status': this.selectedDisciplineWithTeam.liability_status
                }, {
                    preserveScroll: true,
                    preserveState: true,
                });

            },
            toggleSwitchConfirmation() {
                this.switchConfirm = !this.switchConfirm;
            },

            toggleSwitchConfirmNo() {
                this.toggleSwitchConfirmation();
                if(this.switchType == "documentation"){
                    this.selectedDisciplineWithTeam.documentation_status = (this.selectedDisciplineWithTeam.documentation_status == 1) ? 0 : 1;
                }
                else if(this.switchType  == "communication"){
                    this.selectedDisciplineWithTeam.communication_status =  (this.selectedDisciplineWithTeam.communication_status == 1) ? 0 : 1;
                }
                else if(this.switchType  == "liability"){
                    this.selectedDisciplineWithTeam.liability_status =  (this.selectedDisciplineWithTeam.liability_status == 1) ? 0 : 1;
                }
            },

            toggleSwitchConfirmYes() {
                this.toggleSwitchConfirmation();
                this.submit();
            },
            visit(routeName) {
                this.$inertia.visit(
                    this.route(routeName, [this.organization.id, this.project.id]),
                    {
                        preserveState: true,
                        preserveScroll: true,
                    }
                );
            }
        },

        beforeDestroy() {
            mutations.setSelectedProjectDisciplineId(null);
        }
    }
</script>

<style scoped>
    .background {
        background-color: lightgray;
    }

    .tab-background {
        background-color: #EAEFF6;
    }

    .pad-text {
        letter-spacing: 0.1em !important;
    }
    .switch-tabs-row{
        padding-bottom: 0px!important;
    }

    .switch-tabs {
        margin-left: 0px!important;
        margin-bottom: -7px;
    }

    .switch-tab-1 {
        width: 280px;
        display: flex;
        justify-content: center;
    }

    .switch-tab-2 {
        width: 160px;
        display: flex;
        justify-content: center;
    }

    .switch-tab-3 {
        width: 300px;
        display: flex;
        justify-content: center;
    }

    @media  only screen and (max-width: 800px) {
        .switch-tab-1 {
            width: 325px;
            display: flex;
            justify-content: center;
        }

        .switch-tab-2 {
            width: 100px;
            display: flex;
            justify-content: center;
        }

        .switch-tab-3 {
            width: 198.9px;
            display: flex;
            justify-content: center;
        }
    }
</style>
