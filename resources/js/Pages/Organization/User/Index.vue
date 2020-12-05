<template>
    <v-container>
        <v-row justify="center" class="pt-4">
            <v-col cols="3">
                <back-to-projects-button
                    :color="'black'"
                    :click-handler="backToProjects"
                />
            </v-col>
            <v-col cols="6">
                <v-row justify="center">
                    <h3 class="m-0">{{ organization.name }}</h3>
                    <inertia-link
                        v-if="$page.auth.user.can.edit.organizations"
                        class="no-underline"
                        :href="route('organizations.edit', {organization: organization})"
                    >
                        <v-btn min-width="0" small rounded text title="Edit Company Information">
                            <v-icon color="primary darken-2">mdi-settings</v-icon>
                        </v-btn>
                    </inertia-link>
                </v-row>
                <v-row justify="center">
                    <v-subheader class="font-weight-light p-0 pr-2">{{ organization.address_1 }}</v-subheader>
                    <v-divider vertical></v-divider>
                    <v-subheader class="font-weight-light p-0 pl-2">
                        {{organization.city}}, {{organization.state}}, {{organization.zip }}
                    </v-subheader>
                </v-row>
            </v-col>
            <v-col cols="3"></v-col>
        </v-row>

        <v-row justify="center" align="center">

            <v-col cols="12">

                <v-data-table
                    :disable-pagination="true"
                    :hide-default-footer="true"
                    :headers="headers"
                    :items="users"
                    class="transparent"
                >
                    <template v-slot:top>
                        <v-toolbar class="transparent" flat color="white">
                            <v-toolbar-title>Existing Company Users</v-toolbar-title>
                        </v-toolbar>
                    </template>

                    <template v-slot:item.status="{ item }">
                        <v-chip text-color="white" :color="item.isRegistered ? 'green' : 'red'">
                            {{ item.status }}
                        </v-chip>
                    </template>

                    <template v-slot:item.delete="{ item }">
                        <v-icon v-if="item.id !== $page.auth.user.id" @click.stop="deletePrompt(item)">mdi-delete
                        </v-icon>
                    </template>

                </v-data-table>
            </v-col>

        </v-row>

        <!--  Deletion Confirmation prompt    -->

        <v-dialog v-model="dialogOpen" persistent max-width="500px">
            <v-card>
                <v-card-title> Delete User</v-card-title>
                <v-card-text class="pa-4">Are you sure you would like to delete {{ deleteTitle }}?</v-card-text>
                <v-card-actions class="pt-0">
                    <v-spacer></v-spacer>
                    <v-btn color="red darken-1" text @click="deleteUser">Delete</v-btn>
                    <v-btn class="no-underline" color="grey" text @click="cancel">Cancel</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <!--  Show success notification   -->
        <v-snackbar
            v-model="showConfirmation"
            :color="'green'"
            :timeout="2000"
        >
            {{ message }}
        </v-snackbar>

    </v-container>
</template>

<script>
    import Layout from "../../../Shared/Layout";
    import OrganizationHeader from "../../../Shared/OrganizationHeader";
    import PaginatedTable from "../../../Shared/PaginatedTable";
    import BackToProjectsButton from "../../../Shared/BackToProjectsButton";

    export default {
        layout: Layout,
        components: {
            BackToProjectsButton,
            OrganizationHeader,
            PaginatedTable
        },
        props: {
            users: {
                type: Array,
                required: true,
            },
            organization: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                headers: [
                    {
                        text: "Last Name",
                        value: 'last_name',
                        class: 'font-weight-bold body-1',
                    },
                    {
                        text: "First Name",
                        value: 'first_name',
                        class: 'font-weight-bold body-1',
                    },
                    {
                        text: "Email",
                        value: 'email',
                        class: 'font-weight-bold body-1',
                    },
                    {
                        text: "Status",
                        value: 'status',
                        class: 'font-weight-bold body-1',
                    },

                    // {
                    //     text: "Delete",
                    //     value: 'delete',
                    //     class: 'font-weight-bold body-1',
                    //     sortable: false
                    // },
                ],
                dialogOpen: false,
                employeeToDelete: {},
                showConfirmation: false,
                message: null
            }
        },
        methods: {
            deletePrompt(employee) {
                this.dialogOpen = true;
                this.employeeToDelete = employee;
            },
            async deleteUser() {
                this.$inertia.delete(this.route('users.delete', {user: this.employeeToDelete.id}));
            },
            cancel() {
                this.dialogOpen = false;
                this.employeeToDelete = {};
            },
            backToProjects() {
                this.$inertia.visit(this.route('organizations.projects.index', {organization: this.organization.id}));
            }
        },
        created() {
            if (this.$page.flash.success) {
                this.showConfirmation = true;
                this.message = this.$page.flash.success;
            }
        },
        computed: {
            deleteTitle() {
                return this.employeeToDelete.first_name === '' && this.employeeToDelete.last_name === ''
                    ? this.nameOfDeletingEmployee
                    : this.employeeToDelete.email;
            },
            nameOfDeletingEmployee() {
                return `${this.employeeToDelete.first_name} ${this.employeeToDelete.last_name}`;
            }
        }
    }
</script>
