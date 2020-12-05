<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                Daily Log
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="confirmExitDialog = true">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-form v-model="valid" ref="form">
            <div class="py-4 px-3 px-sm-6 px-md-12">

                <v-expansion-panels
                    :value="panels"
                    @change="panels = [0]"
                    multiple
                    flat
                    tile
                >
                    <base-daily-log
                        :were-construction-delays="formData.reportInformation.wereConstructionDelays"
                        :were-construction-issues="formData.reportInformation.wereConstructionIssues"
                        :visitors-today="formData.reportInformation.wereVisitorsToday"
                        :hot-work-today="formData.reportInformation.wasHotWorkPerformed"
                        :safety-meeting-today="formData.reportInformation.wasSafetyMeetingToday"
                    >
                        <template v-slot:reported-by>
                            <span>{{ $page.auth.user.name }} - {{ $page.auth.user.organization.name }}</span>
                        </template>

                        <template v-slot:date-reported>
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
                                        label="Date"
                                        readonly
                                        outlined
                                        dense
                                        :rules="required('Date')"
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
                                    @input="menu = false"
                                    :allowed-dates="allowedDates"
                                    :min="minDate"
                                    :max="new Date().toISOString()"
                                ></v-date-picker>
                            </v-menu>
                        </template>


                        <template v-slot:report-number>
                            <span>DL-{{ discipline.discipline.abbreviation }}-XXX</span>
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

                        <template v-slot:ground-conditions>
                            <v-radio-group
                                v-model="formData.reportInformation.groundConditions"
                                row
                                :rules="required('This field')"
                            >
                                <v-radio v-for="type in groundConditionsTypes"
                                         :label="type.label"
                                         :value="type.value"
                                         :key="type.label"
                                ></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:weather-delay>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wasWeatherDelay"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:working-conditions>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wasUnsafeWorkingConditions"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:description>
                            <v-textarea
                                no-resize
                                placeholder="Description"
                                v-model="formData.reportInformation.description"
                                :rules="required('Description')"
                                outlined
                                dense
                                rows="4"
                                row-height="15"
                            ></v-textarea>
                        </template>

                        <template v-slot:were-construction-issues>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereConstructionIssues"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:construction-issues>
                            <v-textarea
                                no-resize
                                placeholder="Description"
                                v-model="formData.reportInformation.constructionIssues"
                                :rules="formData.reportInformation.wereConstructionIssues ? required('Description') : []"
                                outlined
                                dense
                                rows="4"
                                row-height="15"
                            ></v-textarea>
                        </template>

                        <template v-slot:were-construction-delays>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereConstructionDelays"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:construction-delays>
                            <v-textarea
                                no-resize
                                placeholder="Description"
                                v-model="formData.reportInformation.constructionDelays"
                                :rules="formData.reportInformation.wereConstructionDelays ? required('Description') : []"
                                outlined
                                dense
                                rows="4"
                                row-height="15"
                            ></v-textarea>
                        </template>

                        <template v-slot:visitors-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereVisitorsToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:visitors v-if="formData.reportInformation.wereVisitorsToday">
                            <v-row v-for="(visitor, index) in formData.reportInformation.visitors">
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Name"
                                        v-model="visitor.name"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wereVisitorsToday ? requireWithMaxLength('Name', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Company"
                                        v-model="visitor.company"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wereVisitorsToday ? requireWithMaxLength('Company', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Purpose"
                                        v-model="visitor.purpose"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wereVisitorsToday ? requireWithMaxLength('Purpose', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-icon
                                        v-if="index === 0"
                                        large
                                        color="blue darken-5"
                                        @click="addVisitorRow"
                                    >
                                        mdi-plus-box-outline
                                    </v-icon>
                                    <v-icon
                                        v-else
                                        color="red"
                                        medium
                                        @click="removeVisitorRow(index)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:hot-work-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wasHotWorkPerformed"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:hot-work v-if="formData.reportInformation.wasHotWorkPerformed">
                            <v-row v-for="(hotWork, index) in formData.reportInformation.hotWork">
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Supervisor"
                                        v-model="hotWork.supervisor"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasHotWorkPerformed ? requireWithMaxLength('Supervisor', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Activity"
                                        v-model="hotWork.activity"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasHotWorkPerformed ? requireWithMaxLength('Activity', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Fire Watch"
                                        v-model="hotWork.fireWatch"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasHotWorkPerformed ? requireWithMaxLength('Fire Watch', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-icon
                                        v-if="index === 0"
                                        large
                                        color="blue darken-5"
                                        @click="addHotWorkRow"
                                    >
                                        mdi-plus-box-outline
                                    </v-icon>
                                    <v-icon
                                        v-else
                                        color="red"
                                        medium
                                        @click="removeHotWorkRow(index)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:safety-meeting-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wasSafetyMeetingToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:presenters v-if="formData.reportInformation.wasSafetyMeetingToday">
                            <v-row v-for="(presenter, index) in formData.reportInformation.presenters">
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Presenter"
                                        v-model="presenter.presenter"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasSafetyMeetingToday ? requireWithMaxLength('Presenter', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Company"
                                        v-model="presenter.company"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasSafetyMeetingToday ? requireWithMaxLength('Company', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-text-field
                                        placeholder="Topic"
                                        v-model="presenter.topic"
                                        outlined
                                        dense
                                        :rules="formData.reportInformation.wasSafetyMeetingToday ? requireWithMaxLength('Topic', 255) : []"
                                    ></v-text-field>
                                </v-col>
                                <v-col cols="3">
                                    <v-icon
                                        v-if="index === 0"
                                        large
                                        color="blue darken-5"
                                        @click="addPresenterRow"
                                    >
                                        mdi-plus-box-outline
                                    </v-icon>
                                    <v-icon
                                        v-else
                                        color="red"
                                        medium
                                        @click="removePresenterRow(index)"
                                    >
                                        mdi-delete
                                    </v-icon>
                                </v-col>
                            </v-row>
                        </template>

                        <template v-slot:accidents-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereAccidentsToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:accidents-description>
                            <v-text-field
                                placeholder="Description"
                                v-model="formData.reportInformation.accidentDescription"
                                :disabled="! formData.reportInformation.wereAccidentsToday"
                                :rules="formData.reportInformation.wereAccidentsToday ? required('Description') : []"
                                outlined
                                dense
                            ></v-text-field>
                        </template>

                        <template v-slot:injuries-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereInjuriesToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:injuries-description>
                            <v-text-field
                                placeholder="Description"
                                v-model="formData.reportInformation.injuriesDescription"
                                :disabled="! formData.reportInformation.wereInjuriesToday"
                                :rules="formData.reportInformation.wereInjuriesToday ? required('Description') : []"
                                outlined
                                dense
                            ></v-text-field>
                        </template>

                        <template v-slot:near-misses-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereNearMissesToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:near-misses-description>
                            <v-text-field
                                placeholder="Description"
                                v-model="formData.reportInformation.nearMissesDescription"
                                :disabled="! formData.reportInformation.wereNearMissesToday"
                                :rules="formData.reportInformation.wereNearMissesToday ? required('Description') : []"
                                outlined
                                dense
                            ></v-text-field>
                        </template>

                        <template v-slot:safety-reports-today>
                            <v-radio-group
                                row
                                v-model="formData.reportInformation.wereSafetyReportsToday"
                                :rules="[(val) => val !== null || 'This field is required']"
                            >
                                <v-radio label="Yes" :value="true"></v-radio>
                                <v-radio label="No" :value="false"></v-radio>
                            </v-radio-group>
                        </template>

                        <template v-slot:safety-reports-description>
                            <v-text-field
                                placeholder="Description"
                                v-model="formData.reportInformation.safetyReportDescription"
                                :disabled="! formData.reportInformation.wereSafetyReportsToday"
                                :rules="formData.reportInformation.wereSafetyReportsToday ? required('Description') : []"
                                outlined
                                dense
                            ></v-text-field>
                        </template>

                        <template v-slot:files>
                            <upload-files
                                v-model="files"
                                @remove="removeFile"
                            />
                        </template>

                        <template v-slot:general-comments>
                            <v-textarea
                                no-resize
                                placeholder="General Comments"
                                v-model="formData.reportInformation.generalComments"
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
                                    Submit
                                </v-btn>
                            </v-row>
                        </template>

                    </base-daily-log>
                </v-expansion-panels>
            </div>

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
    import UploadFiles from "../UploadFiles";
    import HandlesFileUploads from "../../mixins/HandlesFileUploads";
    import BaseDailyLog from "./BaseDailyLog";
    import InteractsWithDates from "../../mixins/InteractsWithDates";

    export default {
        components: {
            ConfirmationModal,
            UploadFiles,
            BaseDailyLog
        },

        mixins: [
            HandlesFileUploads,
            InteractsWithDates
        ],

        props: {
            discipline: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                panels: [0],
                groundConditionsTypes: [],
                confirmExitDialog: false,
                dateFormatted: null,
                menu: false,
                valid: false,
                availableDates: [],
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
                        groundConditions: '',
                        wasWeatherDelay: null,
                        wasUnsafeWorkingConditions: null,
                        description: '',
                        wereConstructionIssues: null,
                        constructionIssues: '',
                        wereConstructionDelays: null,
                        constructionDelays: '',
                        wereVisitorsToday: null,
                        visitors: [
                            {
                                name: '',
                                company: '',
                                purpose: ''
                            }
                        ],
                        wasHotWorkPerformed: null,
                        hotWork: [
                            {
                                supervisor: '',
                                activity: '',
                                fireWatch: ''
                            }
                        ],
                        wasSafetyMeetingToday: null,
                        presenters: [
                            {
                                presenter: '',
                                company: '',
                                topic: ''
                            }
                        ],
                        wereAccidentsToday: null,
                        accidentDescription: '',
                        wereInjuriesToday: null,
                        injuriesDescription: '',
                        wereNearMissesToday: null,
                        nearMissesDescription: '',
                        wereSafetyReportsToday: null,
                        safetyReportDescription: '',
                        generalComments: ''
                    }
                }
            }
        },

        computed: {
            minDate() {
                return new Date(this.$page.project.created_at.replace(' ','T')).toISOString();
            },
        },

        // TODO Refactor these required and max length rules out - used multiple places - avoiding refactors for time crunch
        methods: {
            async getGroundConditionTypes() {
                const {data} = await window.axios.get(this.route('ground-condition-types'));

                this.groundConditionsTypes = data;
            },
            async getAvailableDates() {
                const { data } = await window.axios.get(this.route('project-discipline.available-daily-log-dates', [this.discipline.id]));

                this.availableDates = data;
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
            addVisitorRow() {
                this.formData.reportInformation.visitors.push({
                    name: '',
                    company: '',
                    purpose: '',
                });
            },
            removeVisitorRow(index) {
                this.formData.reportInformation.visitors.splice(index, 1);
            },
            addHotWorkRow() {
                this.formData.reportInformation.hotWork.push({
                    supervisor: '',
                    activity: '',
                    fireWatch: ''
                });
            },
            removeHotWorkRow(index) {
                this.formData.reportInformation.hotWork.splice(index, 1);
            },
            addPresenterRow() {
                this.formData.reportInformation.presenters.push({
                    presenter: '',
                    company: '',
                    topic: ''
                });
            },
            removePresenterRow(index) {
                this.formData.reportInformation.presenters.splice(index, 1);
            },
            submit() {
                if (this.$refs.form.validate()) {
                    const formData = new FormData();

                    formData.append('log_date', this.formData.date);
                    formData.append('information', JSON.stringify(this.formData.reportInformation));
                    this.appendFilesToFormData(formData);

                    this.$inertia.post(this.route('liability.daily-logs.store', [this.discipline.id]), formData, {
                        preserveState: true,
                        preserveScroll: true,
                    });

                    this.$emit('close');
                }
            },
            allowedDates(date) {
                return this.availableDates.includes(date);
            }
        },

        created() {
            this.getGroundConditionTypes();
            this.getAvailableDates();
        },

        watch: {
            'formData.reportInformation.wereConstructionIssues': function (val) {
                if (!val) {
                    this.formData.reportInformation.constructionIssues = '';
                }
            },
            'formData.reportInformation.wereConstructionDelays': function (val) {
                if (!val) {
                    this.formData.reportInformation.constructionDelays = '';
                }
            },
            'formData.date': function (val) {
                this.dateFormatted = this.formatDate(this.formData.date);
            },
            'formData.reportInformation.wereVisitorsToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.visitors = [
                        {
                            name: '',
                            company: '',
                            purpose: '',
                        }
                    ];
                }
            },
            'formData.reportInformation.wasHotWorkPerformed': function (val) {
                if (!val) {
                    this.formData.reportInformation.hotWork = [
                        {
                            supervisor: '',
                            activity: '',
                            fireWatch: ''
                        }
                    ];
                }
            },
            'formData.reportInformation.wasSafetyMeetingToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.presenters = [
                        {
                            presenter: '',
                            company: '',
                            topic: ''
                        }
                    ];
                }
            },
            'formData.reportInformation.wereAccidentsToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.accidentDescription = '';
                }
            },
            'formData.reportInformation.wereInjuriesToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.injuriesDescription = '';
                }
            },
            'formData.reportInformation.wereNearMissesToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.nearMissesDescription = '';
                }
            },
            'formData.reportInformation.wereSafetyReportsToday': function (val) {
                if (!val) {
                    this.formData.reportInformation.safetyReportDescription = '';
                }
            }
        },
    }
</script>
