<template>
    <v-row justify="center">
        <v-col cols="10">

            <v-row>
                <v-col cols="12">
                    <h4 class="pb-4">Assign Project Leads</h4>
                </v-col>
            </v-row>

            <v-row v-for="discipline in selectedDisciplines" :key="discipline.id">
                <v-col class="py-0" cols="12">
                    <v-row class="py-0">
                        <v-col class="py-0" cols="5">
                            <span class="subtitle-1"> {{ discipline.label }} Lead</span>
                        </v-col>
                        <v-col class="py-0" cols="5">
                            <span
                                class="subtitle-1"> {{ discipline.lead.first_name }} {{ discipline.lead.last_name }}</span>
                        </v-col>
                        <v-col class="py-0" cols="2">
                            <v-btn text color="primary darken-2" @click="associateDisciplineAndOpenSearch(discipline)">
                                Change
                            </v-btn>
                        </v-col>
                    </v-row>

                    <v-row>
                        <v-col cols="12">
                            <v-divider class="my-0 py-0"></v-divider>
                        </v-col>
                    </v-row>
                </v-col>
            </v-row>

            <!--       User Search Dialog     -->

            <v-dialog v-model="dialog" persistent width="400">
                <user-search
                    title="Change Discipline Lead"
                    subtitle="Search for a registered user within an organization."
                    action-text="Make Lead"
                    @submit="updateLead"
                    @close="dialog = false"
                ></user-search>
            </v-dialog>

            <!--     End User Search Dialog   -->

            <v-row>
                <v-col cols="12">
                    <v-btn
                        color="secondary"
                        text
                        class="mx-0"
                        @click="$emit('back')"
                    >
                        Previous
                    </v-btn>

                    <v-btn
                        class="mx-6"
                        color="primary darken-2"
                        @click="$emit('submit')"
                    >
                        Finish Project Setup
                    </v-btn>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    import UserSearch from "../../../../Shared/UserSearch";

    export default {
        name: "ProjectLeads",
        components: {
            UserSearch
        },
        props: {
            selectedDisciplines: {
                type: Array,
                required: true
            },
        },
        data() {
            return {
                dialog: false,
                disciplineToAssociate: null,
            }
        },
        methods: {
            associateDisciplineAndOpenSearch(discipline) {
                this.dialog = true;
                this.disciplineToAssociate = discipline;
            },
            updateLead(newLead) {
                this.$emit('update-lead', {newLead, discipline_id: this.disciplineToAssociate.id});
                this.dialog = false;
            }
        }
    }
</script>
