<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="$page.auth.user" :date="new Date().toISOString()" highlight></messaging-header>

        <messaging-body-wrapper>
            <v-form v-model="valid" ref="form">
                <v-row class="pt-4">
                    <v-col>
                        <label class="font-weight-bold subtitle-1">
                            Additional Comments:
                        </label>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <v-textarea
                            no-resize
                            placeholder="Description"
                            v-model="comment"
                            :rules="[
                            (val) => !!val || 'Comment is required',
                            (val) => (val || '').length <= 500 || 'Comment must be less than 500 characters'
                        ]"
                            outlined
                            dense
                            rows="4"
                            row-height="15"
                        ></v-textarea>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col>
                        <upload-files
                            v-model="files"
                            @remove="removeFile"
                        />
                    </v-col>
                </v-row>

                <v-divider></v-divider>

                <v-row>
                    <v-btn class="mx-4" min-width="150px" outlined color="blue darken-5"
                           @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                    <v-btn @click="submit" class="white--text" color="blue darken-5" min-width="150px">
                        Submit
                    </v-btn>
                </v-row>
            </v-form>
        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import MessagingHeader from "../Documentation/MessagingHeader";
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import ConfirmationModal from "../Documentation/ConfirmationModal";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            UploadFiles,
            ConfirmationModal
        },

        mixins: [
            HandlesFileUploads
        ],

        props: {
            discipline: {
                type: Object,
                required: true
            },
            log: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                confirmExitDialog: false,
                valid: false,
                comment: ''
            }
        },

        methods: {
            submit() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();

                    formData.append('comment', this.comment);
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('liability.daily-logs.comments.store', [this.discipline.id, this.log.id]), formData, {
                        preserverState: true,
                        preserveScroll: true,
                    });

                    this.$emit('close');
                }
            }
        }
    }
</script>

