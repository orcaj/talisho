<template>
    <v-expansion-panel class="my-2">

        <messaging-header :user="document.assigned" :date="new Date().toISOString()" highlight></messaging-header>

        <messaging-body-wrapper>
            <v-form v-model="valid" ref="form">

                <messaging-top-line :user="document.lead" :document="document"></messaging-top-line>

                <v-divider></v-divider>

                <v-row>
                    <v-col cols="12" sm="6" md="2">
                        <div class="font-weight-bold subtitle-1 pb-3">Due Date:</div>

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
                                :max="maxDate"
                                :allowed-dates="allowedDates"
                            ></v-date-picker>
                        </v-menu>

                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <upload-files v-model="files" @remove="removeFile"></upload-files>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <comments v-model="comments"></comments>
                    </v-col>
                </v-row>

                <v-row>
                    <v-btn class="mx-4" min-width="150px" outlined color="blue darken-5" @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                    <v-btn color="blue darken-5" class="white--text" min-width="150px" @click="createSubmission">
                        Submit
                    </v-btn>
                </v-row>

            </v-form>
        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "./MessagingHeader";
    import MessagingTopLine from "./MessagingTopLine";
    import MessagingBodyWrapper from "./MessagingBodyWrapper";
    import Comments from "../Comments";
    import UploadFiles from '../UploadFiles';
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";

    export default {
        name: "CreateSubmission",

        components: {
            MessagingHeader,
            MessagingTopLine,
            Comments,
            UploadFiles,
            MessagingBodyWrapper
        },

        mixins: [
            InteractsWithDates,
            HandlesFileUploads
        ],

        props: {
            document: {
                type: Object
            }
        },

        data: vm => {
            const now = new Date();
            const oneWeekFromToday = now.setDate(now.getDate() + 7);

            return {
                date: new Date(oneWeekFromToday).toISOString().substr(0, 10),
                dateFormatted: vm.formatDate(new Date(oneWeekFromToday).toISOString().substr(0, 10)),
                menu: false,
                comments: '',
                valid: true,
            };
        },

        methods: {
            createSubmission() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();
                    formData.append('due_date', this.date);
                    formData.append('comments', this.comments);
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('documentation-submissions.store', [this.document.id]), formData, {
                        preserveState: true,
                        preserveScroll: true
                    });

                    this.$emit('close');
                }

            }
        },

        computed: {
            oneWeekFromToday() {
                const now = new Date();
                return now.setDate(now.getDate() + 7);
            },
            threeWeeksFromToday() {
                const now = new Date();
                return now.setDate(now.getDate() + 21);
            },
            minDate() {
                return new Date(this.oneWeekFromToday).toISOString();
            },
            maxDate() {
                return new Date(this.threeWeeksFromToday).toISOString();
            }
        },

        watch: {
            date(val) {
                this.dateFormatted = this.formatDate(this.date)
            },
        },

    }
</script>
