<template>
    <v-expansion-panel width="80%" class="elevation-4 my-4">

        <!--       Invite User Dialog     -->

        <v-dialog v-model="dialog" persistent width="400">
            <user-guest-add
                    @submit="inviteUser"
                    @close="dialog = false"
                    :discipline="discipline"
                    :dialog="dialog"
            ></user-guest-add>
        </v-dialog>

        <!--      End Invite User Dialog   -->

        <!--      Change Lead Dialog     -->
        <v-dialog v-model="changeLeadDialog" persistent width="600">
            <user-search
                    title="The following lead will be changed:"
                    :handle_member="discipline.lead"
                    warning-text="Please select a current team member to inherit all of the removed user's assignments and responsibilities."
                    action-text="Confirm"
                    :discipline="discipline"
                    exclude-message="User already belongs to discipline within the project."
                    @submit="changeLead"
                    @close="changeLeadDialog = false"
            ></user-search>
        </v-dialog>

        <!--      End Change Lead Dialog  -->

        <!--      Remove User Dialog     -->
        <v-dialog v-model="removeUserDialog" persistent width="600">
            <user-search
                    v-if="removeUserDialog"
                    title="The following user will be removed:"
                    :handle_member="selectedUserToRemove"
                    warning-text="Please select a current team member to inherit all of the removed user's assignments and responsibilities."
                    action-text="Confirm"
                    :discipline="discipline"
                    exclude-message="User already belongs to discipline within the project."
                    @submit="reassignUser"
                    @close="closeRemoveUserDialog"

            />
        </v-dialog>

        <!--      End Remove User Dialog  -->

        <v-expansion-panel-header class="blue darken-5 py-0">
            <template v-slot:actions>
                <v-icon class="icon" color="white actions">$expand</v-icon>
            </template>

            <v-row class="info-second px-2" justify="space-between">
                <v-col cols="12" md="8" align-self="center">
                    <h4 class="action-text"> {{ discipline.discipline.label }}</h4>
                </v-col>

                <v-col cols="12" md="2">
                    <div align="right" >
                        <v-btn
                                v-if="$page.auth.user.can.edit.projects || discipline.lead.id === $page.auth.user.id"
                                color="orange actions"
                                class="button-opacity"
                                @click.native.stop="changeLeadDialog = true">
                            <span class="action-button-text">Change Lead</span>
                        </v-btn>
                    </div>
                </v-col>
                <v-col cols="12" md="1" align-self="center">
                    <div>
                        <v-btn v-if="$page.auth.user.can.edit.projects || discipline.lead.id === $page.auth.user.id"
                               color="orange actions"
                               class="button-opacity mx-3"
                               @click.native.stop="dialog = true">
                            <span class="action-button-text">Invite Guest</span>
                        </v-btn>
                    </div>
                </v-col>
                <v-col cols="12" md="1">
                    &nbsp;
                </v-col>
            </v-row>

        </v-expansion-panel-header>

        <v-expansion-panel-content class="px-md-4 pt-6">
            <!--       Team listing           -->

            <template v-for="user in [discipline.lead].concat(sortedTeam)">
                <v-row>
                    <v-col class="py-2 py-md-0" cols="12" md="2" align-self="center">
                        <span
                                class="subtitle-1"
                                :class="user.id == discipline.lead.id ? 'lead-text' :''"
                        >
                            {{user.first_name}} {{user.last_name}}
                        </span>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="2" offset-md="0" align-self="center">
                        <span
                                class="subtitle-1 px-0 px-md-2"
                                :class="user.id == discipline.lead.id ? 'lead-text' :''"
                        > {{ user.company.name || 'N/A' }}</span>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="2" offset-md="0" align-self="center">
                        <span
                                class="subtitle-1 px-0 px-md-2"
                                :class="user.id == discipline.lead.id ? 'lead-text' :''"
                        >
                            {{ user.email || 'N/A' }}
                        </span>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="2" offset-md="0" align-self="center">
                        <div
                                class="subtitle-1 px-0 px-md-2"
                                :class="user.id == discipline.lead.id ? 'lead-text' :''"
                        > {{ user.mobile_phone }}</div>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="2" offset-md="0" align="right">
                        <v-chip
                                class="white--text mx-7"
                                close-icon="mdi-delete"
                                color="blue  darken-5"
                                label
                                v-if="user.id == discipline.lead.id"
                        >
                            Lead&nbsp;
                        </v-chip>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="1" offset-md="0" align="center">
                            <v-chip
                                    class="white--text guest-chip"
                                    close-icon="mdi-delete"
                                    color="blue darken-5"
                                    label
                                    v-if="user.id != discipline.lead.id && user.isRegistered"
                            >
                                &nbsp;&nbsp;Guest&nbsp;&nbsp;
                            </v-chip>
                            <v-chip
                                    class="white--text guest-chip"
                                    close-icon="mdi-delete"
                                    color="red"
                                    label
                                    v-else-if="user.id != discipline.lead.id && ($page.auth.user.can.edit.projects || discipline.lead.id === $page.auth.user.id) && user.isExpired"
                                    @click="openResendEmail(user)"
                            >
                                Re-Send
                            </v-chip>
                            <v-chip
                                    class="white--text guest-chip"
                                    close-icon="mdi-delete"
                                    color="red"
                                    label
                                    v-else-if="user.id != discipline.lead.id"
                            >
                                Pending
                            </v-chip>
                    </v-col>
                    <v-col class="py-2 py-md-0" cols="12" md="1" offset-md="0" align-self="center">
                        <div align="right">
                            <v-btn
                                    v-if="user.id !== discipline.lead.id && ($page.auth.user.id === discipline.lead.id || $page.auth.user.can.edit.projects)"
                                    text
                                    color="blue darken-5"
                                    class="delete-chip"
                                    @click="openRemoveUserDialog(user)"
                            >
                                <v-icon
                                        medium
                                        color="red darken-2"
                                >
                                    delete
                                </v-icon>
                            </v-btn>
                        </div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <v-divider class="my-0 py-0"></v-divider>
                    </v-col>
                </v-row>
            </template>

        </v-expansion-panel-content>

    </v-expansion-panel>
</template>

<script>
    import UserGuestAdd from "./UserGuestAdd";
    import UserSearch from "./UserSearch";

    export default {

        components: {
            UserGuestAdd,
            UserSearch
        },

        props: {
            discipline: {
                type: Object,
                required: true
            },
            allUsersInProject: {
                type: Array,
                default: () => []
            },
            allLeadsInProject: {
                type: Array,
                default: () => []
            },
            projectManager: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                dialog: false,
                changeLeadDialog: false,
                removeUserDialog: false,
                selectedUserToRemove: null,
            }
        },

        computed: {
            teamIncludingLead() {
                return [this.discipline.lead].concat(this.allUsersInProject);
            },
            teamIncludingLeadIds() {
                return this.teamIncludingLead.map(({id}) => id);
            },
            otherDisciplineTeamsAndLeadsIds() {
                return this.teamIncludingLeadIds.concat(this.allLeadIds).concat(this.projectManager.id).filter((id) => !this.teamIds.includes(id));
            },
            teamIncludingAllProjectLeadsIds() {
                return this.allLeadsInProject.concat(this.discipline.team).concat(this.projectManager).map(({id}) => id);
            },
            allLeadIds() {
                return this.allLeadsInProject.map(({id}) => id);
            },
            teamIds() {
                return this.discipline.team.map(({id}) => id);
            },
            sortedTeam() {
                for (var i=0; i <this.discipline.team.length; i++) {
                    if (this.discipline.team[i].id == this.discipline.lead.id) {
                        this.discipline.team.splice(i, 1);
                    }
                }
                return this.discipline.team.sort((prevUser, nextUser) => {
                    const prevLastName = prevUser.last_name.toUpperCase();
                    const nextLastName = nextUser.last_name.toUpperCase();
                    return (prevLastName < nextLastName) ? -1 : (prevLastName > nextLastName) ? 1 : 0;
                });
            }
        },

        methods: {
            changeLead(user) {
                this.$inertia.put(this.route('project-discipline.change-lead', [this.discipline.id]), {
                    lead_id: user.id
                }, {
                    preserveScroll: true,
                    preserveState: true,
                });

                this.changeLeadDialog = false;
            },
            reassignUser(user) {
                this.$inertia.put(this.route('project-discipline.users.update', [this.discipline.id, this.selectedUserToRemove.id]), {
                    'new_user_id': user.id
                }, {
                    preserveState: true,
                    preserveScroll: true,
                });

                this.closeRemoveUserDialog();
            },
            inviteUser(user) {
                this.$inertia.post(this.route('project-discipline.users.create', [this.discipline.id, user.id]), {
                    preserveScroll: true,
                    preserveState: true,
                    only: ['disciplines']
                });
                this.dialog = false;
            },
            openRemoveUserDialog(user) {
                this.removeUserDialog = true;
                this.selectedUserToRemove = user;
            },

            openResendEmail(user) {
                this.$inertia.post(this.route('project-discipline.users.resend', [this.discipline.id, user.id]),  {
                    preserveState: true,
                    preserveScroll: true,
                });
            },

            closeRemoveUserDialog() {
                this.removeUserDialog = false;
                this.selectedUserToRemove = null;
            },
        },

    }
</script>

<style scoped>
    .icon-first {
        order: 0;
    }

    .info-second {
        order: 1;
    }
    .action-text{
        color: white!important;
    }
    .guest-chip{
        margin-left:58px;
    }
    .delete-chip{
        margin-left:58px;
    }
    .lead-text{
        font-weight:900;
    }
</style>
