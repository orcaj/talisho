<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                IR-{{ incidentReport.identifier }}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="$emit('close')">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <base-incident-report :sought-medical-aid="incidentReport.information.soughtMedicalAid">
            <template v-slot:reported-by>
                <span>{{ incidentReport.reported_by.name }} - {{ incidentReport.reported_by.organization.name }}</span>
            </template>

            <template v-slot:date-reported>
                {{ formatDate(incidentReport.created_at) }}
            </template>

            <template v-slot:report-number>
                <span>IR-{{ incidentReport.identifier }}</span>
            </template>

            <template v-slot:weather-high>
                <span>{{ incidentReport.information.weather.high }}</span>
            </template>

            <template v-slot:weather-low>
                <span>{{ incidentReport.information.weather.low }}</span>
            </template>

            <template v-slot:weather-precipitation>
                <span>{{ incidentReport.information.weather.precipitation }}</span>
            </template>

            <template v-slot:weather-wind>
                <span>{{ incidentReport.information.weather.wind }}</span>
            </template>

            <template v-slot:weather-conditions>
                <span>{{ incidentReport.information.weather.conditions }}</span>
            </template>

            <template v-slot:incident-type>
                <span class="text-capitalize my-4">{{ incidentReport.information.type }}</span>
            </template>

            <template v-slot:name>
                <span>{{ incidentReport.information.name }}</span>
            </template>

            <template v-slot:company>
                <span>{{ incidentReport.information.company }}</span>
            </template>

            <template v-slot:job-title>
                <span>{{ incidentReport.information.jobTitle }}</span>
            </template>

            <template v-slot:supervisor>
                <span>{{ incidentReport.information.supervisor }}</span>
            </template>

            <template v-slot:supervisor-informed>
                <span>{{ incidentReport.information.supervisorInformed ? 'Yes' : 'No' }}</span>
            </template>

            <template v-slot:date-of-injury>
                <span> {{ formatDate(incidentReport.incident_date) }} </span>
            </template>

            <template v-slot:time-of-injury>
                <span> {{ formatTime(incidentReport.information.time) }} </span>
            </template>

            <template v-slot:witnesses>
                <div
                    v-for="witness in incidentReport.information.witnesses"
                >
                    {{ witness.name }}
                </div>
            </template>

            <template v-slot:location>
                <span> {{ incidentReport.information.location }}</span>
            </template>

            <template v-slot:task>
                <span> {{ incidentReport.information.task }} </span>
            </template>

            <template v-slot:description>
                <span> {{ incidentReport.information.details }} </span>
            </template>

            <template v-slot:prevention>
                <span> {{ incidentReport.information.prevention }}</span>
            </template>

            <template v-slot:injured-parts>
                <span> {{ incidentReport.information.injuredParts }}</span>
            </template>

            <template v-slot:injured-before>
                <span> {{ incidentReport.information.hasBeenInjuredBefore ? 'Yes' : 'No' }} </span>
            </template>

            <template v-slot:sought-aid>
                <span> {{ incidentReport.information.soughtMedicalAid ? 'Yes' : 'No' }}</span>
            </template>

            <template v-slot:medical-name>
                <span> {{ incidentReport.information.medicalAidName }}</span>
            </template>

            <template v-slot:medical-when>
                <span> {{ medicalDate }}</span>
            </template>

            <template v-slot:files>
                <div class="subtitle-1 font-weight-bold pb-2">Files: </div>
                <attached-files :files="incidentReport.files" />
            </template>

            <template v-slot:resulting-actions>
                <span> {{ incidentReport.information.futureActions}}</span>
            </template>

            <template v-slot:action-buttons>
                <v-row class="py-4">
                    <v-btn
                        class="mx-4 white--text"
                        min-width="150px"
                        color="blue darken-5"
                        @click="$emit('close')"
                    >
                        Close
                    </v-btn>
                </v-row>
            </template>
        </base-incident-report>

    </v-card>
</template>

<script>
    import BaseIncidentReport from "./BaseIncidentReport";
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import InteractsWithTime from "../../mixins/InteractsWithTime";
    import AttachedFiles from "../AttachedFiles";

    export default {
        components: {
            BaseIncidentReport,
            AttachedFiles
        },

        mixins: [
            InteractsWithDates,
            InteractsWithTime
        ],

        props: {
            incidentReport: {
                type: Object,
                required: true
            }
        },

        computed: {
            medicalDate() {
                try {
                    return this.formatDate(this.incidentReport.information.medicalAidWhen);
                } catch {
                    return this.incidentReport.information.medicalAidWhen;
                }
            }
        }

    }
</script>
