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
                    <inertia-link class="no-underline" :href="route('organizations.projects.documentation.index', {organization: $page.auth.user.organization.id, project: project.id})">
                        {{ project.name }}
                    </inertia-link>
                </h6>
            </v-col>
        </v-row>

        <v-row>
            <v-col cols="12">
                <v-card>
                    <v-card-title class="mx-4 headline">
                        Edit Project
                    </v-card-title>

                    <general-info
                        :general-info.sync="formData.generalInfo"
                        :editing="true"
                        @next="submit"
                    ></general-info>
                </v-card>
            </v-col>
        </v-row>

    </v-container>
</template>

<script>
    import GeneralInfo from "./SaveSteps/GeneralInfo";
    import Layout from "../../../Shared/Layout";

    export default {
        layout: Layout,

        components: {
            GeneralInfo
        },

        props: {
            project: {
                type: Object,
                required: true
            },
            organization: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
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
                }
            }
        },

        methods: {
            fillProjectData() {
                this.formData = {
                    generalInfo: {
                        clientName: this.project.client_name,
                        projectName: this.project.name,
                        city: this.project.city,
                        street: this.project.address_1,
                        state: this.project.state,
                        zip: this.project.zip,
                        description: this.project.description
                    },
                }
            },
            submit() {
                this.$inertia.visit(this.route('organizations.projects.update', {organization: this.organization.id, project: this.project.id}), {
                    data: this.formData,
                    method: 'put'
                });
            }
        },

        created() {
            this.fillProjectData();
        }
    }
</script>

