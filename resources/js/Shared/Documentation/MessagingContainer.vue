<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">{{ document.entity.specification.name }} ({{ document.entity.specification.code }})
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>


        <div class="py-4 px-3 px-sm-6 px-md-12">
            <v-expansion-panels v-model="panels" multiple flat tile>

                <template v-if="document.submissions.length > 0">
                    <template v-for="submission in document.submissions">
                        <view-submission
                            v-if="!(document.approved_by_upload && document.submissions.length === 1)"
                            :document="document"
                            :submission="submission"
                        />

                        <template v-if="!submission.response">
                            <create-response
                                v-if="isUnderReviewForUser && !documentIsDeleted"
                                :document="document"
                                :submission="submission"
                                @cancel="dialog = true"
                                @close="$emit('close')"
                            />
                        </template>
                        <template v-else>
                            <view-response
                                :response="submission.response"
                                :highlight="submission.response.approvesDocument"
                            />
                        </template>

                    </template>
                </template>

                <create-submission
                    v-if="isOutstandingForActiveUser && !documentIsDeleted"
                    :document="document"
                    @cancel="dialog = true"
                    @close="$emit('close')"
                ></create-submission>
                <v-expansion-panel readonly v-else-if="document.submissions.length === 0">
                    <v-expansion-panel-content>
                        <v-col cols="12">
                            <v-row justify="center">
                                <v-icon x-large color="blue darken-5">mdi-email-alert</v-icon>
                            </v-row>
                            <v-row justify="center">
                                <div class="headline">Awaiting Response</div>
                            </v-row>
                        </v-col>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </div>

        <div v-if="document.isApproved || documentIsDeleted" class="px-3 px-sm-6 px-md-12 pt-2 pb-4">
            <v-btn min-width="200px" color="blue darken-5" class="white--text" @click="$emit('close')">Close</v-btn>
        </div>

        <confirmation-modal
            :dialog="dialog"
            @close="dialog = false"
            @confirm="$emit('close')"
        />

    </v-card>
</template>

<script>
    import CreateSubmission from "./CreateSubmission";
    import ViewSubmission from "./ViewSubmission";
    import CreateResponse from "./CreateResponse";
    import ViewResponse from "./ViewResponse";
    import ConfirmationModal from "./ConfirmationModal";

    export default {
        components: {
            CreateSubmission,
            ViewSubmission,
            CreateResponse,
            ViewResponse,
            ConfirmationModal
        },

        props: ['document'],

        data() {
            return {
                panels: [0],
                dialog: false
            }
        },

        computed: {
            isOutstandingForActiveUser() {
                return this.document.isOutstanding && this.$page.auth.user.id === this.document.assigned_user_id
            },
            isUnderReviewForUser() {
                return this.document.isUnderReview && this.$page.auth.user.id === this.document.lead_user_id
            },
            shouldConfirmExit() {
                return this.isOutstandingForActiveUser || this.isUnderReviewForUser;
            },
            documentIsDeleted() {
                return !!this.document.deleted_at;
            }
        },

        methods: {
            setDefaultOpenPanels() {
                if (this.document.submissions.length === 0) {
                    return [0];
                }

                if (this.document.submissions.length === 1 && this.document.approved_by_upload) {
                    return [0];
                }

                const arr = [];

                this.document.submissions.forEach((sub, index) => {
                    if (this.lastElementOfArray(arr)) {
                        arr.push(this.lastElementOfArray(arr) + 1);
                    } else {
                        arr.push(0);
                    }

                    if (!!sub.response) {
                        arr.push(this.lastElementOfArray(arr) + 1);
                    }
                });

                return arr.slice(-1).concat(this.lastElementOfArray(arr) + 1);
            },
            lastElementOfArray(arr) {
                if (arr.length === 0) {
                    return undefined;
                }

                return arr[arr.length - 1];
            },
            handleClose() {
                if (this.shouldConfirmExit) {
                    this.dialog = true;
                    return;
                }

                this.$emit('close');
            }
        },

        created() {
            this.panels = this.setDefaultOpenPanels();
        }
    }
</script>
