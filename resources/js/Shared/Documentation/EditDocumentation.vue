<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                Designate New Assignee
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div class="mx-9 mx-md-12">
            <v-form v-model="valid" ref="form">
                <v-row align="center">
                    <v-col cols="6">
                        <div class="py-2 font-weight-bold subtitle-1">Assign selected documents to:</div>
                        <v-select
                            v-model="selectedUserId"
                            :items="team"
                            :rules="[
                                (val) => !!val || 'Must select guest to re-assign document to'
                            ]"
                            width="200"
                            placeholder="Choose guest..."
                            item-value="id"
                            outlined
                            dense
                        >
                            <template v-slot:item="{ item }">
                                <div>{{ item.name }}</div>
                            </template>
                            <template v-slot:selection="{ item }">
                                <div> {{ item.name }}</div>
                            </template>
                        </v-select>
                    </v-col>
                    <v-col cols="6" class="pb-0">
                        <v-row justify="end" align="center">
                            <v-btn
                                class="ml-0 mr-4 mb-2 mx-sm-4 mb-sm-0 no-underline"
                                min-width="125px"
                                outlined
                                color="blue darken-5"
                                @click="handleClose"
                            >
                                Cancel
                            </v-btn>
                            <v-btn
                                class="ml-0 mb-2 mx-sm-0 mb-sm-0 white--text no-underline"
                                color="blue darken-5"
                                min-width="125px"
                                @click="reAssignDocument"
                            >
                                Save Changes
                            </v-btn>

                        </v-row>
                    </v-col>
                </v-row>
            </v-form>
        </div>



        <!--  Confirm exit modal      -->
        <confirmation-modal
            :dialog="dialog"
            @close="dialog = false"
            @confirm="$emit('close')"
        />
        <!--  End confirm exit modal      -->

    </v-card>
</template>

<script>
    import ConfirmationModal from "./ConfirmationModal";
    import InteractsWithDates from "../../mixins/InteractsWithDates";

    export default {
        components: {
            ConfirmationModal
        },

        mixins: [
            InteractsWithDates
        ],

        props: {
            documentationIds: {
                type: Array,
                required: true
            },
            team: {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                dialog: false,
                otherDialog: false,
                selectedUserId: null,
                valid: false
            }
        },

        methods: {
            handleClose() {
                this.dialog = true;
            },
            reAssignDocument() {
                if (this.$refs.form.validate()) {
                    this.$inertia.put(this.route('documentation.re-assign.multiple'), {
                        'assigned_user_id': this.selectedUserId,
                        'documentation_ids': this.documentationIds
                    });

                    this.$emit('submitted');
                }
            },
        }
    }
</script>
