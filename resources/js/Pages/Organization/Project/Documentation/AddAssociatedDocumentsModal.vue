<template>
    <v-card>
        <v-toolbar dark color="blue darken-5" class="px-4">
            <v-toolbar-title>
                Add Additional Associated Documents
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-card-text>
            <v-form v-model="valid" ref="form">
                <document-quick-list
                    v-if="!!quickList && quickList.length > 0"
                    title="Associated Documents"
                    :already-selected-codes="alreadySelectedCsiCodes"
                    :quick-list="quickList"
                    :selected="selectedQuickListCsis"
                    @selected="updateSelectedQuickList"
                ></document-quick-list>

            </v-form>
        </v-card-text>

        <div class="mx-8 py-4">
            <v-row>
                <v-btn
                    class="ml-0 mr-4 mb-2 mx-sm-4 mb-sm-0 no-underline"
                    min-width="150px"
                    outlined
                    color="blue darken-5"
                    @click="handleClose"
                >
                    Cancel
                </v-btn>
                <v-btn
                    class="ml-0 mb-2 mx-sm-0 mb-sm-0 white--text no-underline"
                    color="blue darken-5"
                    min-width="150px"
                    @click="submitAdditionalDocuments"
                >
                    Submit
                </v-btn>
            </v-row>
        </div>

        <!--     Confirm Exit Modal      -->
        <confirmation-modal
            :dialog="dialog"
            @close="dialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
import ConfirmationModal from "../../../../Shared/Documentation/ConfirmationModal";
import DocumentQuickList from "../../../../Shared/DocumentQuickList";

export default {
    name: "AddAssociatedDocumentsModal",

    components: {
        ConfirmationModal,
        DocumentQuickList
    },

    props: {
        document: {
            type: Object,
            required: true
        }
    },

    computed: {
        quickListCsiIds() {
            return this.quickList.map(({id}) => id);
        },
        alreadySelectedCsiCodes() {
            return this.document.associated_documents.map(({csi_id}) => csi_id);
        }
    },

    data() {
        return {
            dialog: false,
            valid: true,
            selectedQuickListCsis: [],
            quickList: null
        }
    },

    methods: {
        handleClose() {
            this.dialog = true;
        },
        async fetchAssociatedDocumentsList() {
            const {data} = await window.axios.get(this.route(`associated-documents`));
            this.quickList = data;
        },
        updateSelectedQuickList(selected) {
            this.selectedQuickListCsis = selected;
        },
        submitAdditionalDocuments() {
            if (this.selectedQuickListCsis.length > 0) {
                this.$inertia.post(this.route('add-associated-documents', [this.document.id]), {
                    associated_document_ids: this.selectedQuickListCsis.map(({id}) => id)
                });

                this.$emit('submitted');
            }
        }
    },

    created() {
        this.fetchAssociatedDocumentsList();
    }

}
</script>
