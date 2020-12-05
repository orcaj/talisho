<template>
    <v-card>
        <v-toolbar dark color="blue darken-5" class="px-4">
            <v-toolbar-title>
                {{ document.entity.specification.name }} ({{ document.entity.specification.code }})
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-card-text class="mt-4">
            <v-form v-model="valid" ref="form">
                <div class="my-2">
                    <div class="text--black subtitle-1 text-center">This Document has not yet been submitted.</div>
                    <div class="font-weight-light font-italic text-center">Bypass messaging process and manually upload
                        this
                        document.
                    </div>
                </div>

                <upload-files
                    v-model="files"
                    :rules="[
                        (val) => val.length > 0 || 'File required to approve document'
                    ]"
                    @remove="removeFile"
                />

                <comments v-model="comments"/>
            </v-form>
        </v-card-text>

        <div class="mx-8 py-8">
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
                    @click="uploadApprovedDocument"
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
import ConfirmationModal from "./ConfirmationModal";
import UploadFiles from "../UploadFiles";
import HandlesFileUploads from "../../mixins/HandlesFileUploads";
import Comments from "../Comments";

export default {
    name: "ManualApproval",

    components: {
        Comments,
        UploadFiles,
        ConfirmationModal
    },

    mixins: [
        HandlesFileUploads
    ],

    props: {
        document: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            dialog: false,
            comments: '',
            valid: true
        }
    },

    methods: {
        handleClose() {
            this.dialog = true;
        },
        uploadApprovedDocument() {
            if (this.$refs.form.validate()) {
                const formData = new FormData();
                formData.append('comments', this.comments);
                this.appendFilesToFormData(formData);

                this.$inertia.post(this.route('documentation.manual-approval', [this.document.id]), formData, {
                    preserveState: true,
                    preserveScroll: true
                });

                this.$emit('close');
            }
        }
    }

}
</script>
