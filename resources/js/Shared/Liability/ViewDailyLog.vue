<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                DL-{{ log.identifier }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="confirmExitDialog = true">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div class="py-4 px-12">
            <v-expansion-panels v-model="panels" multiple tile flat>
                <base-daily-log
                    :were-construction-issues="log.information.wereConstructionIssues"
                    :were-construction-delays="log.information.wereConstructionDelays"
                    :visitors-today="log.information.wereVisitorsToday"
                    :hot-work-today="log.information.wasHotWorkPerformed"
                    :safety-meeting-today="log.information.wasSafetyMeetingToday"
                >
                    <template v-slot:reported-by>
                        <span>{{ log.reported_by.name }} - {{ log.reported_by.organization.name}}</span>
                    </template>

                    <template v-slot:date-reported>
                        <span>{{ formatDate(log.log_date) }}</span>
                    </template>

                    <template v-slot:report-number>
                        <span>DL-{{ log.identifier }}</span>
                    </template>

                    <template v-slot:weather-high>
                        <div> {{ log.information.weather.high }} </div>
                    </template>

                    <template v-slot:weather-low>
                        <div> {{ log.information.weather.low }} </div>
                    </template>

                    <template v-slot:weather-precipitation>
                        <div> {{ log.information.weather.precipitation }} </div>
                    </template>

                    <template v-slot:weather-wind>
                        <div> {{ log.information.weather.wind }} </div>
                    </template>

                    <template v-slot:weather-conditions>
                        <div> {{ log.information.weather.conditions }} </div>
                    </template>

                    <template v-slot:ground-conditions>
                        <span class="text-capitilize ml-6 my-4"> {{ log.information.groundConditions }} </span>
                    </template>

                    <template v-slot:weather-delay>
                        <span class="ml-6">{{ log.information.wasWeatherDelay ? 'Yes' : 'No' }}</span>
                    </template>

                    <template v-slot:working-conditions>
                        <span class="ml-6">{{ log.information.wasUnsafeWorkingConditions ? 'Yes' : 'No' }}</span>
                    </template>

                    <template v-slot:description>
                        <div>{{ log.information.description }}</div>
                    </template>

                    <template v-slot:were-construction-issues>
                        <div>{{ log.information.wereConstructionIssues ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:construction-issues>
                        <div> {{ log.information.constructionIssues}}</div>
                    </template>

                    <template v-slot:were-construction-delays>
                        <div>{{ log.information.wereConstructionDelays ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:construction-delays>
                        <div> {{ log.information.constructionDelays}}</div>
                    </template>

                    <template v-slot:visitors-today>
                        <div>{{ log.information.wereVisitorsToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:visitors>
                        <v-row v-for="visitor in log.information.visitors">
                            <v-col cols="3"> {{ visitor.name }}</v-col>
                            <v-col cols="3"> {{ visitor.company }}</v-col>
                            <v-col cols="3"> {{ visitor.purpose }}</v-col>
                        </v-row>
                    </template>

                    <template v-slot:hot-work-today>
                        <div>{{ log.information.wasHotWorkPerformed ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:hot-work>
                        <v-row v-for="hotWork in log.information.hotWork">
                            <v-col cols="3"> {{ hotWork.supervisor }}</v-col>
                            <v-col cols="3"> {{ hotWork.activity }}</v-col>
                            <v-col cols="3"> {{ hotWork.fireWatch }}</v-col>
                        </v-row>
                    </template>

                    <template v-slot:safety-meeting-today>
                        <div>{{ log.information.wasSafetyMeetingToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:presenters>
                        <v-row v-for="presenter in log.information.presenters">
                            <v-col cols="3"> {{ presenter.presenter }}</v-col>
                            <v-col cols="3"> {{ presenter.company }}</v-col>
                            <v-col cols="3"> {{ presenter.topic }}</v-col>
                        </v-row>
                    </template>

                    <template v-slot:accidents-today>
                        <div>{{ log.information.wereAccidentsToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:accidents-description>
                        <div>{{ log.information.accidentDescription }}</div>
                    </template>

                    <template v-slot:injuries-today>
                        <div> {{ log.information.wereInjuriesToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:injuries-description>
                        <div> {{ log.information.injuriesDescription }}</div>
                    </template>

                    <template v-slot:near-misses-today>
                        <div> {{ log.information.wereNearMissesToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:near-misses-description>
                        <div> {{ log.information.nearMissesDescription }}</div>
                    </template>

                    <template v-slot:safety-reports-today>
                        <div>{{ log.information.wereSafetyReportsToday ? 'Yes' : 'No' }}</div>
                    </template>

                    <template v-slot:safety-reports-description>
                        <div> {{ log.information.safetyReportDescription }}</div>
                    </template>

                    <template v-slot:files>
                        <div class="subtitle-1 font-weight-bold pb-2">Files: </div>
                        <attached-files :files="log.files" />
                    </template>

                    <template v-slot:general-comments>
                        <div> {{ log.information.generalComments }}</div>
                    </template>

                </base-daily-log>

                <view-daily-log-comment
                    v-for="comment in log.comments"
                    :key="comment.id"
                    :comment="comment"
                />

                <add-daily-log-comment
                    @cancel="confirmExitDialog = true"
                    :log="log"
                    :discipline="discipline"
                    @close="$emit('close')"
                />
            </v-expansion-panels>
        </div>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
    import BaseDailyLog from "./BaseDailyLog";
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import ConfirmationModal from "../Documentation/ConfirmationModal";
    import AttachedFiles from "../AttachedFiles";
    import AddDailyLogComment from "./AddDailyLogComment";
    import ViewDailyLogComment from "./ViewDailyLogComment";

    export default {
        components: {
            ConfirmationModal,
            BaseDailyLog,
            AttachedFiles,
            AddDailyLogComment,
            ViewDailyLogComment
        },

        mixins: [
            InteractsWithDates
        ],

        props: {
            log: {
                type: Object,
                required: true
            },
            discipline: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                panels: [],
                confirmExitDialog: false
            }
        },

        methods: {
            setOpenPanels() {
                const comments = this.log.comments || [];
                // existing log open and viewable
                this.panels.push(0);

                // each comment card open and viewable
                comments.forEach((comment, index) => {
                    this.panels.push(index + 1);
                });

                // finally, the add comment card should default open and viewable
                this.panels.push(comments.length + 1);
            }
        },

        created() {
            this.setOpenPanels();
        }

    }
</script>
