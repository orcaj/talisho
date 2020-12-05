<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                Incident Report
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="confirmExitDialog = true">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-form v-model="valid" ref="form">

            <base-incident-report :sought-medical-aid="formData.reportInformation.soughtMedicalAid">
                <template v-slot:reported-by>
                    <span>{{ $page.auth.user.name }} - {{ $page.auth.user.organization.name }}</span>
                </template>

                <template v-slot:date-reported>
                    {{ formatDate(new Date().toISOString()) }}
                </template>

                <template v-slot:report-number>
                    <span>IR-{{ discipline.discipline.abbreviation }}-XXX</span>
                </template>

                <template v-slot:weather-high>
                    <v-text-field
                        placeholder="High"
                        v-model="formData.reportInformation.weather.high"
                        :rules="requireWithMaxLength('High', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:weather-low>
                    <v-text-field
                        placeholder="Low"
                        v-model="formData.reportInformation.weather.low"
                        :rules="requireWithMaxLength('Low', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:weather-precipitation>
                    <v-text-field
                        placeholder="Precipitation"
                        v-model="formData.reportInformation.weather.precipitation"
                        :rules="requireWithMaxLength('Precipitation', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:weather-wind>
                    <v-text-field
                        placeholder="Wind"
                        v-model="formData.reportInformation.weather.wind"
                        :rules="requireWithMaxLength('Wind', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:weather-conditions>
                    <v-textarea
                        no-resize
                        placeholder="General Conditions"
                        v-model="formData.reportInformation.weather.conditions"
                        :rules="requireWithMaxLength('General Conditions', 500)"
                        outlined
                        dense
                        rows="3"
                        row-height="10"
                    ></v-textarea>
                </template>

                <template v-slot:incident-type>
                    <v-radio-group
                        v-model="formData.reportInformation.type"
                        row
                        :rules="required('Incident Type')"
                    >
                        <v-radio v-for="type in incidentReportTypes"
                                 :label="type.label"
                                 :value="type.value"
                                 :key="type.label"
                        ></v-radio>
                    </v-radio-group>
                </template>

                <template v-slot:name>
                    <v-text-field
                        placeholder="Name"
                        v-model="formData.reportInformation.name"
                        :rules="requireWithMaxLength('Name', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:company>
                    <v-text-field
                        placeholder="Company"
                        v-model="formData.reportInformation.company"
                        :rules="requireWithMaxLength('Company', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:job-title>
                    <v-text-field
                        placeholder="Job Title"
                        v-model="formData.reportInformation.jobTitle"
                        :rules="requireWithMaxLength('Job Title', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:supervisor>
                    <v-text-field
                        placeholder="Supervisor"
                        v-model="formData.reportInformation.supervisor"
                        :rules="requireWithMaxLength('Supervisor', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:supervisor-informed>
                    <v-radio-group
                        row
                        v-model="formData.reportInformation.supervisorInformed"
                        :rules="[(val) => val !== null || 'This field is required']"
                    >
                        <v-radio label="Yes" :value="true"></v-radio>
                        <v-radio label="No" :value="false"></v-radio>
                    </v-radio-group>
                </template>

                <template v-slot:date-of-injury>
                    <v-menu
                        ref="menu1"
                        v-model="menu1"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="dateFormatted"
                                label="Date of Injury"
                                readonly
                                outlined
                                dense
                                :rules="required('Date of Injury')"
                                @blur="formData.date = parseDate(dateFormatted)"
                                v-on="on"
                            >
                                <template v-slot:append>
                                    <v-icon v-on="on">mdi-calendar</v-icon>
                                </template>
                            </v-text-field>
                        </template>
                        <v-date-picker
                            v-model="formData.date"
                            no-title
                            @input="menu1 = false"
                            :min="minDate"
                            :max="new Date().toISOString()"
                        ></v-date-picker>
                    </v-menu>
                </template>

                <template v-slot:time-of-injury>
                    <v-menu
                        ref="menu2"
                        v-model="menu2"
                        :close-on-content-click="false"
                        :nudge-right="40"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                :value="timeFormatted"
                                label="Time of injury"
                                outlined
                                dense
                                readonly
                                :rules="required('Time of Injury')"
                                v-on="on"
                            >
                                <template v-slot:append>
                                    <v-icon v-on="on">mdi-clock-outline</v-icon>
                                </template>
                            </v-text-field>
                        </template>
                        <v-time-picker
                            v-if="menu2"
                            no-title
                            v-model="formData.reportInformation.time"
                            full-width
                        ></v-time-picker>
                    </v-menu>
                </template>

                <template v-slot:witnesses>
                    <v-row
                        v-for="(witness, index) in formData.reportInformation.witnesses"
                        justify="center"
                        align="center"
                    >
                        <v-col cols="10">
                            <v-text-field
                                v-model="witness.name"
                                placeholder="Name"
                                :hide-details="'auto'"
                                :rules="requireWithMaxLength('Witness Name', 255)"
                                outlined
                                dense
                            ></v-text-field>
                        </v-col>
                        <v-col cols="2">
                            <v-icon
                                v-if="index === 0"
                                large
                                color="blue darken-5"
                                @click="addWitnessRow"
                            >
                                mdi-plus-box-outline
                            </v-icon>
                            <v-icon
                                v-else
                                color="red"
                                medium
                                @click="removeWitnessRow(index)"
                            >
                                mdi-delete
                            </v-icon>
                        </v-col>
                    </v-row>
                </template>

                <template v-slot:location>
                    <v-text-field
                        placeholder="Location"
                        v-model="formData.reportInformation.location"
                        :rules="requireWithMaxLength('Location', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:task>
                    <v-text-field
                        placeholder="Task"
                        v-model="formData.reportInformation.task"
                        :rules="requireWithMaxLength('Task', 255)"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:description>
                    <v-textarea
                        no-resize
                        placeholder="Description"
                        v-model="formData.reportInformation.details"
                        :rules="requireWithMaxLength('Details', 500)"
                        outlined
                        dense
                        rows="4"
                        row-height="15"
                    ></v-textarea>
                </template>

                <template v-slot:prevention>
                    <v-textarea
                        no-resize
                        placeholder="Description"
                        v-model="formData.reportInformation.prevention"
                        :rules="requireWithMaxLength('Description', 500)"
                        outlined
                        dense
                        rows="4"
                        row-height="15"
                    ></v-textarea>
                </template>

                <template v-slot:injured-parts>
                    <v-textarea
                        no-resize
                        placeholder="Description"
                        v-model="formData.reportInformation.injuredParts"
                        :rules="requireWithMaxLength('Description', 500)"
                        outlined
                        dense
                        rows="4"
                        row-height="15"
                    ></v-textarea>
                </template>

                <template v-slot:injured-before>
                    <v-radio-group
                        row
                        v-model="formData.reportInformation.hasBeenInjuredBefore"
                        :rules="[(val) => val !== null || 'This field is required']"
                    >
                        <v-radio label="Yes" :value="true"></v-radio>
                        <v-radio label="No" :value="false"></v-radio>
                    </v-radio-group>
                </template>

                <template v-slot:sought-aid>
                    <v-radio-group
                        row
                        v-model="formData.reportInformation.soughtMedicalAid"
                        :rules="[(val) => val !== null || 'This field is required']"
                    >
                        <v-radio label="Yes" :value="true"></v-radio>
                        <v-radio label="No" :value="false"></v-radio>
                    </v-radio-group>
                </template>

                <template v-slot:medical-name>
                    <v-text-field
                        placeholder="Name"
                        v-model="formData.reportInformation.medicalAidName"
                        :rules="formData.reportInformation.soughtMedicalAid ? requireWithMaxLength('Name', 255) : []"
                        outlined
                        dense
                    ></v-text-field>
                </template>

                <template v-slot:medical-when>
                    <v-menu
                        ref="menu3"
                        v-model="menu3"
                        :close-on-content-click="false"
                        transition="scale-transition"
                        offset-y
                        max-width="290px"
                        min-width="290px"
                    >
                        <template v-slot:activator="{ on }">
                            <v-text-field
                                v-model="medicalAidWhenDateFormatted"
                                label="Date of Visit"
                                readonly
                                outlined
                                dense
                                :rules="formData.reportInformation.soughtMedicalAid ? required('When') : []"
                                @blur="formData.reportInformation.medicalAidWhen = parseDate(medicalAidWhenDateFormatted)"
                                v-on="on"
                            >
                                <template v-slot:append>
                                    <v-icon v-on="on">mdi-calendar</v-icon>
                                </template>
                            </v-text-field>
                        </template>
                        <v-date-picker
                            v-model="formData.reportInformation.medicalAidWhen"
                            no-title
                            @input="menu3 = false"
                            :min="minDate"
                            :max="new Date().toISOString()"
                        ></v-date-picker>
                    </v-menu>
                </template>

                <template v-slot:files>
                    <upload-files
                        v-model="files"
                        @remove="removeFile"
                    />
                </template>

                <template v-slot:resulting-actions>
                    <v-textarea
                        no-resize
                        placeholder="Description"
                        v-model="formData.reportInformation.futureActions"
                        :rules="requireWithMaxLength('Description', 500)"
                        outlined
                        dense
                        rows="4"
                        row-height="15"
                    ></v-textarea>
                </template>

                <template v-slot:action-buttons>
                    <v-row class="py-4">
                        <v-btn class="ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0" min-width="150px" outlined color="blue darken-5"
                               @click="confirmExitDialog = true">
                            Cancel
                        </v-btn>
                        <v-btn class="ml-4 mb-2 mx-sm-0 mb-sm-0 white--text" @click="submit" color="blue darken-5" min-width="150px">
                            File Incident Report
                        </v-btn>
                    </v-row>
                </template>
            </base-incident-report>

        </v-form>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
    import ConfirmationModal from "../Documentation/ConfirmationModal";
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import BaseIncidentReport from "./BaseIncidentReport";
    import InteractsWithTime from "../../mixins/InteractsWithTime";

    export default {
        components: {
            ConfirmationModal,
            UploadFiles,
            BaseIncidentReport
        },

        mixins: [
            InteractsWithDates,
            HandlesFileUploads,
            InteractsWithTime
        ],

        props: {
            discipline: {
                type: Object,
                required: true
            }
        },


        data: vm => {
            return {
                dateFormatted: null,
                medicalAidWhenDateFormatted: null,
                confirmExitDialog: false,
                valid: false,
                incidentReportTypes: [],
                menu1: false,
                menu2: false,
                menu3: false,
                timeFormatted: null,
                formData: {
                    date: null,
                    reportInformation: {
                        weather: {
                            high: '',
                            low: '',
                            wind: '',
                            precipitation: '',
                            conditions: ''
                        },
                        type: '',
                        time: null,
                        name: '',
                        company: '',
                        jobTitle: '',
                        supervisor: '',
                        supervisorInformed: null,
                        witnesses: [
                            {
                                name: ''
                            },
                        ],
                        location: '',
                        task: '',
                        details: '',
                        prevention: '',
                        injuredParts: '',
                        hasBeenInjuredBefore: null,
                        soughtMedicalAid: null,
                        medicalAidName: '',
                        medicalAidWhen: null,
                        futureActions: ''
                    }
                }
            }
        },

        computed: {
            labelStyling() {
                return 'subtitle-1 font-weight-bold';
            },
            minDate() {
                return new Date(this.$page.project.created_at.replace(' ','T')).toISOString();
            },
        },

        methods: {
            async getIncidentReportTypes() {
                const {data} = await window.axios.get(this.route('incident-report-types'));

                this.incidentReportTypes = data;
            },
            requireWithMaxLength(field, length) {
                return this.required(field).concat(this.maxLength(field, length));
            },
            maxLength(field, length) {
                return [
                    val => (val || '').length <= length || `${field} must be ${length} characters or less`,
                ];
            },
            required(field) {
                return [
                    val => !!val || `${field} is required.`
                ];
            },
            addWitnessRow() {
                this.formData.reportInformation.witnesses.push(
                    {
                        name: ''
                    }
                );
            },
            removeWitnessRow(index) {
                this.formData.reportInformation.witnesses.splice(index, 1);
            },
            submit() {
                if (this.$refs.form.validate()) {

                    const formData = new FormData();

                    formData.append('incident_date', this.formData.date);
                    formData.append('information', JSON.stringify(this.formData.reportInformation));
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('liability.incident-report.store', [this.discipline.id]), formData, {
                        preserveState: true,
                        preserveScroll: true,
                    });

                    this.$emit('close');
                }
            }
        },

        created() {
            this.getIncidentReportTypes();
        },

        watch: {
            'formData.date': function (val) {
                this.dateFormatted = this.formatDate(this.formData.date);
            },
            'formData.reportInformation.medicalAidWhen': function (val) {
                this.medicalAidWhenDateFormatted = this.formatDate(this.formData.reportInformation.medicalAidWhen);
            },
            'formData.reportInformation.time': function (val) {
                this.timeFormatted = this.formatTime(this.formData.reportInformation.time);
            },
            'formData.reportInformation.soughtMedicalAid': function (val) {
                if (!val) {
                    this.formData.reportInformation.medicalAidWhen = null;
                    this.formData.reportInformation.medicalAidName = '';
                }
            }
        }

    }
</script>
