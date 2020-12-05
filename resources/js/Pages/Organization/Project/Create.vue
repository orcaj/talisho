<template>
    <v-container>
        <v-row justify="space-between" class="pt-4">
            <v-col cols="8">
                <h6 class="font-weight-light">
                    <inertia-link
                        class="no-underline"
                        :href="route('organizations.projects.index', {organization: $page.auth.user.organization.id})">
                        My Projects
                    </inertia-link>
                    <span>
                        /
                    </span>
                    <span> New Project </span>
                </h6>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-title class="mx-4 headline">
                        New Project Setup
                    </v-card-title>
                        <v-stepper v-model="currentStep">
                            <v-stepper-header>
                                <v-stepper-step
                                    step="1"
                                    editable
                                >
                                    GENERAL PROJECT INFORMATION
                                </v-stepper-step>
                                <v-stepper-step
                                    step="2"
                                    :editable="generalInfoValid"
                                >
                                    SELECT PROJECT DISCIPLINES
                                </v-stepper-step>
                                <v-stepper-step
                                    step="3"
                                    :editable="formData.disciplineData.selected.length > 0 && generalInfoValid"
                                >
                                    ASSIGN PROJECT LEADS
                                </v-stepper-step>
                            </v-stepper-header>

                            <v-stepper-content step="1">
                                <general-info
                                    :general-info.sync="formData.generalInfo"
                                    @valid="updateGeneralInfoStatus"
                                    @next="next"
                                ></general-info>
                            </v-stepper-content>

                            <v-stepper-content step="2">
                                <disciplines
                                    :discipline-data.sync="formData.disciplineData"
                                    :disciplines="disciplines"
                                    :default-disciplines="defaultDisciplines"
                                    :project-manager="projectManager"
                                    @next="next"
                                    @back="back"
                                    @toggled="toggleDefault"
                                    @select-change="setSelectedDisciplines"
                                ></disciplines>
                            </v-stepper-content>

                            <v-stepper-content step="3">
                                <project-leads
                                    :selected-disciplines="formData.disciplineData.selected"
                                    @update-lead="updateLead"
                                    @back="back"
                                    @submit="submit"
                                ></project-leads>
                            </v-stepper-content>
                        </v-stepper>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
    import Layout from "../../../Shared/Layout";
    import Disciplines from "./SaveSteps/Disciplines";
    import GeneralInfo from "./SaveSteps/GeneralInfo";
    import ProjectLeads from "./SaveSteps/ProjectLeads";

    export default {
        layout: Layout,
        components: {
            Disciplines,
            GeneralInfo,
            ProjectLeads
        },
        props: {
            disciplines: {
                type: Array,
                required: true
            },
            defaultDisciplines: {
                type: Array,
                default: () => []
            },
            projectManager: {
                type: Object,
                required: true
            },
            routes: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                currentStep: 1,
                generalInfoValid: false,
                formData: {
                    generalInfo: {
                        clientName: '',
                        projectName: '',
                        city: '',
                        street: '',
                        state: '',
                        zip: '',
                        description: ''
                    },
                    disciplineData: {
                        selected: [],
                        saveDefaults: true,
                    },
                }
            }
        },
        created() {
            this.formData.disciplineData.selected = this.defaultDisciplines;
        },
        methods: {
            next() {
                this.currentStep = parseInt(this.currentStep) + 1;
            },
            back() {
                this.currentStep = parseInt(this.currentStep) - 1;
            },
            updateGeneralInfoStatus(value) {
                this.generalInfoValid = value;
            },
            toggleDefault(value) {
                this.formData.disciplineData.saveDefaults = value;
            },
            setSelectedDisciplines(value) {
                this.formData.disciplineData.selected = value;
            },
            updateLead({newLead, discipline_id}) {
                const index = this.formData.disciplineData.selected.findIndex(item => item.id === discipline_id);
                this.formData.disciplineData.selected[index].lead = newLead;
            },
            submit() {
                this.$inertia.visit(this.routes.submit.route, {
                    method: this.routes.submit.method,
                    data: this.formData
                });
            }
        }
    }
</script>
