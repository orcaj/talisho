<template>
    <v-expansion-panel>
        <messaging-header
            :user="document.lead"
            :date="new Date().toISOString()"
            highlight
        />

        <messaging-body-wrapper>
            <v-form v-model="valid" ref="form">
                <v-row>
                    <v-col cols="12" md="7">
                        <div class="font-weight-bold subtitle-1 pt-3">Status:</div>

                        <v-radio-group
                            v-model="status"
                            :rules="[(val) => !!val || 'Status is required.']"
                        >
                            <v-row>
                                <v-col cols="6">
                                    <v-radio
                                        v-for="status in splitList[0]"
                                        color="blue darken-5"
                                        :key="status.label"
                                        :label="status.label"
                                        :value="status.label"
                                        class="my-4"
                                    >
                                        <template v-slot:label>
                                            <div class="black--text">{{ status.label }}</div>
                                        </template>
                                    </v-radio>
                                </v-col>
                                <v-col cols="6">
                                    <v-radio
                                        v-for="status in splitList[1]"
                                        color="blue darken-5"
                                        :key="status.label"
                                        :label="status.label"
                                        :value="status.label"
                                        class="my-4"
                                    >
                                        <template v-slot:label>
                                            <div class="black--text">{{ status.label }}</div>
                                        </template>
                                    </v-radio>
                                </v-col>
                            </v-row>
                        </v-radio-group>
                    </v-col>
                    <v-col cols="12" md="5">
                        <div v-if="document.entity_type === 'App\\Submittal'" class="grey lighten-3 p-3 my-2 text-uppercase">
                            Submittal was reviewed for design conformity and general conformance to the contract
                            documents only. The contractor is responsible for confirming and correlating dimensions at
                            the jobsite for tolerance, clearance, quantities, fabrication processes and techniques of
                            construction, coordination of their work with other trades and full compliance with the
                            contract documents.
                        </div>
                    </v-col>
                </v-row>

                <v-divider></v-divider>

                <v-row>
                    <v-col cols="12">
                        <v-row v-if="status && !statusApprovesDocument">
                            <v-col cols="4">
                                <span class="font-weight-medium py-2">Due Date</span>
                                <v-menu
                                    ref="menu"
                                    v-model="menu"
                                    :close-on-content-click="false"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            v-model="dateFormatted"
                                            readonly
                                            outlined
                                            dense
                                            @blur="date = parseDate(dateFormatted)"
                                            v-on="on"
                                        >
                                            <template v-slot:append>
                                                <v-icon v-on="on">mdi-calendar</v-icon>
                                            </template>
                                        </v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="date"
                                        no-title
                                        @input="menu = false"
                                        :min="minDate"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                        </v-row>

                        <upload-files
                            v-model="files"
                            @remove="removeFile"
                            :rules="fileUploadRule()"
                        ></upload-files>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <comments v-model="comments" required></comments>
                    </v-col>
                </v-row>

                <v-row>
                    <v-btn class="mx-4" min-width="150px" outlined color="blue darken-5" @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-5" class="white--text" min-width="150px" @click="createResponse">
                        Submit
                    </v-btn>
                </v-row>
            </v-form>
        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
import MessagingHeader from "./MessagingHeader";
import MessagingBodyWrapper from "./MessagingBodyWrapper";
import UploadFiles from "../UploadFiles";
import Comments from "../Comments";
import HandlesFileUploads from "../../mixins/HandlesFileUploads";
import HandlesDocumentationDateSelection from "../../mixins/HandlesDocumentationDateSelection";

export default {
    components: {
        MessagingHeader,
        UploadFiles,
        Comments,
        MessagingBodyWrapper,
    },

    mixins: [
        HandlesFileUploads,
        HandlesDocumentationDateSelection
    ],

    props: {
        document: {
            type: Object,
            required: true
        },
        submission: {
            type: Object,
            required: true
        }
    },

    data() {
        return {
            comments: '',
            statuses: [],
            valid: true,
            status: null,
        }
    },

    computed: {
        splitList() {
            if (this.statuses.length <= 2) {
                // just have one column if there are no more than 2 options available
                return [this.statuses, []];
            }

            const midPoint = Math.ceil(this.statuses.length / 2);
            return [this.statuses.slice(0, midPoint), this.statuses.slice(midPoint)];
        },
        statusApprovesDocument() {
            const status = this.statuses.find(status => status.label === this.status);
            return status ? status.approves : false;
        }
    },

    methods: {
        async getStatusOptions() {
            const routeName = this.document.entity_type === 'App\\Submittal' ? 'statuses.submittals' : 'statuses.other-documents';
            const {data} = await window.axios.get(this.route(routeName));

            this.statuses = data;
        },
        createResponse() {
            if (this.$refs.form.validate()) {
                const formData = new FormData();
                formData.append('status', this.status);
                formData.append('comments', this.comments);
                formData.append('due_date', this.statusApprovesDocument ? null : this.date);
                this.appendFilesToFormData(formData);

                this.$inertia.post(this.route('documentation-responses.store', [this.submission.id]), formData,
                    {
                        preserveState: true,
                        preserveScroll: true,
                    });

                this.$emit('close');
            }
        },
        fileUploadRule() {
            return [
                (val) => {
                    if (this.statusApprovesDocument) {
                        return val.length > 0 || 'File required to approve document';
                    } else {
                        return true;
                    }
                }
            ]
        },
    },

    created() {
        this.getStatusOptions();
    },

    watch: {
        status(val) {
            this.$refs.form.resetValidation();
        }
    }
}
</script>

