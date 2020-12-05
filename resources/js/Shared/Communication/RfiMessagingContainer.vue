<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                {{ !!rfi ? `RFI ${rfi.identifier}` : 'New RFI'}}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div v-if="!!rfi" class="py-4 elevation-4">
            <span class="subtitle-1 font-weight-bold pl-10 pr-2">Status:</span>
            <v-tooltip v-if="rfi.isUnderReview" bottom>
                <template v-slot:activator="{ on }">
                    <v-chip v-on="on" color="orange lighten-3"><span class="font-weight-bold">Under Review</span>
                    </v-chip>
                </template>
                <span>RFI has been submitted and is currently being reviewed.</span>
            </v-tooltip>

            <v-tooltip v-else-if="rfi.isFinalized" bottom>
                <template v-slot:activator="{ on }">
                    <v-chip v-on="on" color="green lighten-3"><span class="font-weight-bold">Finalized</span></v-chip>
                </template>
                <span>RFI has been responded to.</span>
            </v-tooltip>
        </div>

        <div class="py-4 px-3 px-sm-6 px-md-12">
            <v-expansion-panels v-model="panels" multiple flat tile>
                <create-rfi
                    v-if="!rfi"
                    @cancel="confirmExitDialog = true"
                    @close="$emit('close')"
                    :discipline="discipline"
                />

                <template v-else-if="!!rfi">
                    <view-rfi
                        :rfi="rfi"
                    />
                    <create-rfi-response
                        v-if="!rfi.response && $page.auth.user.id === rfi.project_discipline.lead.id"
                        :rfi="rfi"
                        @close="$emit('close')"
                        @cancel="confirmExitDialog = true"
                    />
                    <view-rfi-response
                        v-else-if="!!rfi.response"
                        :rfi="rfi"
                        @close="$emit('close')"
                    />
                </template>
            </v-expansion-panels>
        </div>

        <div v-if="!!rfi && rfi.isFinalized" class="px-12 pt-2 pb-4">
            <v-btn min-width="200px" color="blue darken-5" class="white--text" @click="$emit('close')">Close</v-btn>
        </div>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
    import ConfirmationModal from "../Documentation/ConfirmationModal";
    import CreateRfi from "./CreateRfi";
    import ViewRfi from "./ViewRfi";
    import CreateRfiResponse from "./CreateRfiResponse";
    import ViewRfiResponse from "./ViewRfiResponse";

    export default {
        components: {
            CreateRfi,
            ConfirmationModal,
            ViewRfi,
            CreateRfiResponse,
            ViewRfiResponse
        },

        props: {
            discipline: {
                type: Object
            },
            rfi: {
                type: Object,
                default: null
            }
        },

        data() {
            return {
                panels: [0, 1],
                confirmExitDialog: false
            }
        },

        computed: {
            shouldConfirmExit() {
                // if no rfi exists already OR an RFI exists but has no response and User is lead
                return !this.rfi || (!!this.rfi && !this.rfi.response && this.$page.auth.user.id === this.rfi.project_discipline.lead.id);
            }
        },

        methods: {
            handleClose() {
                if (this.shouldConfirmExit) {
                    this.confirmExitDialog = true;
                    return;
                }

                this.$emit('close');
            }
        }
    }
</script>
