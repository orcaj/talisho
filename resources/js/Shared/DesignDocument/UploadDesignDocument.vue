<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title>
                Upload {{ documentType }}
            </v-toolbar-title>
        </v-toolbar>

        <v-card-text>
            <v-form v-model="valid" ref="form">
                <div class="font-weight-bold subtitle-1 pt-4">Name</div>

                <v-text-field
                    v-model="name"
                    class="pb-2"
                    color="blue darken-5"
                    placeholder="Name..."
                    :rules="[
                        (val) => !!val || 'Name is required'
                    ]"
                />
                <upload-files
                    v-model="files"
                    @remove="removeFile"
                    :rules="[
                        (val) => val.length > 0 || 'File is required for upload!'
                    ]"
                />
            </v-form>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="secondary"
                text
                class="mx-0 px-2"
                @click="confirmExitDialog = true"
            >
                Cancel
            </v-btn>
            <v-btn
                class="mx-4 px-2 white--text"
                color="blue darken-5"
                @click="upload"
            >
                Upload
            </v-btn>
        </v-card-actions>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />

    </v-card>
</template>

<script>
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import ConfirmationModal from "../Documentation/ConfirmationModal";

    export default {
        components: {
            UploadFiles,
            ConfirmationModal
        },

        mixins: [
            HandlesFileUploads
        ],

        props: {
            documentType: {
                type: String,
                required: true
            }
        },

        data() {
            return {
                name: '',
                valid: false,
                confirmExitDialog: false
            }
        },

        methods: {
            upload() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();
                    formData.append('name', this.name);
                    formData.append('type', this.documentType);
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('organizations.projects.design-documents.store', [this.$page.organization.id, this.$page.project.id]), formData, {
                        preserveState: true,
                        preserveScroll: true,
                    });

                    this.$emit('close');
                }
            }
        }
    }
</script>
