<template>
    <v-expansion-panel class="my-2">
        <messaging-header
            :user="$page.auth.user"
            :date="new Date().toISOString()"
            highlight
        ></messaging-header>

        <messaging-body-wrapper>
            <v-form ref="form" v-model="valid" class="py-6">
                <upload-files v-model="files" @remove="removeFile"/>

                <v-row>
                    <v-col cols="12">
                        <comments
                            v-model="response"
                            title="Response"
                            required
                        ></comments>
                    </v-col>
                </v-row>

                <v-row>
                    <v-btn class="ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0" min-width="150px" outlined color="blue darken-5"
                           @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                    <v-btn class="ml-4 mb-2 mx-sm-0 mb-sm-0 white--text" @click="submitResponse" color="blue darken-5" min-width="150px">
                        Respond
                    </v-btn>
                </v-row>
            </v-form>
        </messaging-body-wrapper>

    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "../Documentation/MessagingHeader";
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import Comments from "../Comments";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            UploadFiles,
            Comments
        },

        mixins: [
            HandlesFileUploads
        ],

        props: {
            rfi: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                response: '',
                valid: false
            }
        },

        methods: {
            submitResponse() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();
                    formData.append('response', this.response);
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('communication.rfi.response.store', [this.rfi.project_discipline.id, this.rfi.id]), formData, {
                        preserveState: true,
                        preserveScroll: true
                    });

                    this.$emit('close');
                }
            }
        }
    }
</script>
