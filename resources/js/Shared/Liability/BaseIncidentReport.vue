<template>
    <div class="py-6 px-3 px-sm-6 px-md-12">
        <div class="content-outline px-8 py-3">
            <v-row>
                <v-col cols="5" sm="4" md="2">
                    <span :class="labelStyling">
                        Reported By:
                    </span>
                </v-col>
                <v-col cols="7" sm="5" md="4">
                    <slot name="reported-by"></slot>
                </v-col>
                <v-col cols="5" class="d-sm-none">
                    <span :class="labelStyling">
                        Report Date:
                    </span>
                </v-col>
                <v-col cols="7" sm="3" md="2" offset="0" offset-sm="0" offset-md="4" align-self="center">
                    <slot name="date-reported"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="5" sm="4" md="2">
                    <span :class="labelStyling">Report No.:</span>
                </v-col>
                <v-col cols="7" sm="5" md="4">
                    <slot name="report-number"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="12" md="2">
                    <div :class="labelStyling">
                        Weather data:
                    </div>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <div :class="labelStyling + ' pb-2'">
                        High
                    </div>
                    <slot name="weather-high"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <div :class="labelStyling + ' pb-2'">
                        Low
                    </div>
                    <slot name="weather-low"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <div :class="labelStyling + ' pb-2'">
                        Precipitation
                    </div>
                    <slot name="weather-precipitation"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <div :class="labelStyling + ' pb-2'">
                        Wind
                    </div>
                    <slot name="weather-wind"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="11" md="8" offset="1" offset-md="2">
                    <div :class="labelStyling + ' pb-2'">
                        General Conditions
                    </div>
                    <slot name="weather-conditions"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <div :class="labelStyling">
                        Reporting a work-related:
                    </div>
                    <slot name="incident-type"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" md="3">
                    <div :class="labelStyling + ' pb-2'">
                        Name of Injured Individual
                    </div>
                    <slot name="name"></slot>
                </v-col>
                <v-col cols="12" md="3">
                    <div :class="labelStyling + ' pb-2'">
                        Company
                    </div>
                    <slot name="company"></slot>
                </v-col>
                <v-col cols="12" md="3">
                    <div :class="labelStyling + ' pb-2'">
                        Job Title
                    </div>
                    <slot name="job-title"></slot>
                </v-col>
                <v-col cols="12" md="3">
                    <div :class="labelStyling + ' pb-2'">
                        Supervisor
                    </div>
                    <slot name="supervisor"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div :class="labelStyling + ' pb-2'">
                        Has supervisor been informed?
                    </div>
                    <slot name="supervisor-informed"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="12" sm="5" md="3">
                    <div :class="labelStyling + ' pb-2'">
                        Date of Injury:
                    </div>
                    <slot name="date-of-injury"></slot>
                </v-col>

                <v-col cols="12" sm="5" md="3" offset="0" offset-sm="1">
                    <div :class="labelStyling + ' pb-2'">
                        Time of Injury:
                    </div>
                    <slot name="time-of-injury"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="11" sm="8" md="4">
                    <div :class="labelStyling + ' pb-2'">
                        Name of Witnesses:
                    </div>
                    <slot name="witnesses"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" sm="8" md="4">
                    <div :class="labelStyling + ' pb-2'">
                        Location on-site where injury occurred:
                    </div>
                    <slot name="location"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" sm="8" md="4">
                    <div :class="labelStyling + ' pb-2'">
                        Task being performed at time of injury:
                    </div>
                    <slot name="task"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <div :class="labelStyling + ' pb-2'">
                        Provide a detailed, step-by-step description of how the injury occurred:
                    </div>
                    <slot name="description"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div :class="labelStyling + ' pb-2'">
                        Describe below what could have been done (if anything) to have prevented this injury:
                    </div>
                    <slot name="prevention"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div :class="labelStyling + ' pb-2'">
                        What part(s) of the body were injured? If a near-miss, what could have been injured?
                    </div>
                    <slot name="injured-parts"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <div :class="labelStyling">
                        Has this part of the body been injured before?
                    </div>
                    <slot name="injured-before"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div :class="labelStyling">
                        Have you seen a doctor, or sought medical aid?
                    </div>
                    <slot name="sought-aid"></slot>
                </v-col>
            </v-row>

            <v-row v-if="soughtMedicalAid">
                <v-col cols="4">
                    <div :class="labelStyling + ' pb-2'">
                        If YES, whom did you see?
                    </div>
                    <slot name="medical-name"></slot>
                </v-col>
                <v-col cols="4">
                    <div :class="labelStyling + ' pb-2'">
                        If YES, when?
                    </div>
                    <slot name="medical-when"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <slot name="files"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div :class="labelStyling + ' pb-2'">
                        Resulting action(s) executed, planned, or recommended to prevent similar, future injury
                        or near miss?
                    </div>
                    <slot name="resulting-actions"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <slot name="action-buttons"></slot>
        </div>
    </div>

</template>

<script>
    export default {
        props: {
            soughtMedicalAid: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            labelStyling() {
                return 'subtitle-1 font-weight-bold';
            },
        },

    }
</script>


<style scoped>
    .content-outline {
        border: thin solid hsl(0, 0%, 45.1%);
    }
</style>
