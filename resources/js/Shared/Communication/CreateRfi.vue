<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="$page.auth.user" :date="new Date().toISOString()" highlight></messaging-header>

        <messaging-body-wrapper>
            <v-form v-model="valid" ref="form">

                <v-row class="py-2">
                    <v-col cols="5" sm="3" md="2">
                        <span class="font-weight-bold subtitle-1">To:</span>
                    </v-col>
                    <v-col cols="7" sm="9" md="4">
                        <div>{{ discipline.lead.name }}</div>
                        <div> {{ discipline.lead.organization.name }}</div>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="5" sm="3" md="2">
                        <span class="font-weight-bold subtitle-1">RFI No.:</span>
                    </v-col>
                    <v-col cols="7" sm="9" md="4">
                        <div>RFI-{{ discipline.discipline.abbreviation }}-XXX</div>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="5" sm="3" md="2">
                        <span class="font-weight-bold subtitle-1">Discipline:</span>
                    </v-col>
                    <v-col cols="7" sm="9" md="4">
                        <div> {{ discipline.discipline.label }} </div>
                    </v-col>
                </v-row>

                <v-divider></v-divider>

                <v-row>
                    <v-col cols="12" sm="8" md="5" class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Subject:</div>

                        <v-text-field
                            v-model="subject"
                            outlined
                            dense
                            placeholder="Subject..."
                            :rules="[
                                (val) => val.length < 255 || 'Subject must be less than 255 characters.',
                                (val) => !!val || 'Subject required.'
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="8" md="5" class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Drawing No.:</div>

                        <v-text-field
                            v-model="drawingNumber"
                            outlined
                            dense
                            placeholder="Drawing Number..."
                            :rules="[
                                (val) => val.length < 255 || 'Subject must be less than 255 characters.',
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="8" md="5" class="py-0">
                        <div class="font-weight-bold subtitle-1 pb-3">Specification No.:</div>

                        <v-text-field
                            v-model="specificationNumber"
                            outlined
                            dense
                            placeholder="Specification Number..."
                            :rules="[
                                (val) => val.length < 255 || 'Subject must be less than 255 characters.',
                            ]"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" sm="6" md="3" class="py-0">
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
                                    outlined
                                    dense
                                    readonly
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
                        <upload-files v-model="files" @remove="removeFile"/>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12">
                        <comments
                            v-model="question"
                            title="Question"
                            required
                        ></comments>
                    </v-col>
                </v-row>

                <v-row>
                    <v-btn @click="createRfi" color="blue darken-5" class="ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0 white--text" min-width="150px">
                        Submit
                    </v-btn>
                    <v-btn class="ml-4 mb-2 mx-sm-0 mb-sm-0" min-width="150px" outlined color="blue darken-5"
                           @click="$emit('cancel')">
                        Cancel
                    </v-btn>
                </v-row>

            </v-form>
        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "../Documentation/MessagingHeader";
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import Comments from "../Comments";
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";

    export default {
        components: {
            MessagingBodyWrapper,
            MessagingHeader,
            Comments,
            UploadFiles
        },

        mixins: [
            InteractsWithDates,
            HandlesFileUploads
        ],

        props: {
            discipline: {
                type: Object,
                required: true
            }
        },

        data: vm => {
            function isWeekend(day) {
                // day 6 is Saturday, day 0 is Sunday
                return day === 6 || day === 0;
            }

            const twoDaysFromToday = new Date().setDate(new Date().getDate() + 2);
            const threeDaysFromToday = new Date().setDate(new Date().getDate() + 3);
            const fourDaysFromToday = new Date().setDate(new Date().getDate() + 4);

            const defaultDate = isWeekend(new Date(twoDaysFromToday).getDay())
                ? (isWeekend(new Date(threeDaysFromToday).getDay()) ? fourDaysFromToday : threeDaysFromToday)
                : twoDaysFromToday;

            return {
                date: new Date(defaultDate).toISOString().substr(0, 10),
                dateFormatted: vm.formatDate(new Date(defaultDate).toISOString().substr(0, 10)),
                menu: false,
                subject: '',
                drawingNumber: '',
                specificationNumber: '',
                files: [],
                question: '',
                valid: true,
            };
        },

        computed: {
            twoDaysFromToday() {
                const now = new Date();
                return now.setDate(now.getDate() + 2);
            },
            tenDaysFromToday() {
                const now = new Date();
                return now.setDate(now.getDate() + 10);
            },
            minDate() {
                return new Date(this.twoDaysFromToday).toISOString();
            },
            maxDate() {
                return new Date(this.tenDaysFromToday).toISOString();
            }
        },

        methods: {
            createRfi() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();
                    formData.append('subject', this.subject);
                    formData.append('drawing_number', this.drawingNumber);
                    formData.append('specification_number', this.specificationNumber);
                    formData.append('due_date', this.date);
                    formData.append('question', this.question);
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('communication.rfi.store', [this.discipline.id]), formData, {
                        preserveState: true,
                        preserveScroll: true,
                    });

                    this.$emit('close');
                }
            }
        },

        watch: {
            date(val) {
                this.dateFormatted = this.formatDate(this.date)
            },
        },

    }
</script>
