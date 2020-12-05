<template>
    <v-expansion-panel>
        <v-expansion-panel-header class="content-outline">
            <v-col>
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
                    <v-col cols="7" sm="3" md="3" lg="2" offset="0" offset-sm="0" offset-md="3" offset-lg="4" align-self="center">
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
            </v-col>
        </v-expansion-panel-header>

        <v-expansion-panel-content class="body-outline">

            <v-row>
                <v-col cols="12" md="2">
                    <label :class="labelStyling">
                        Weather data:
                    </label>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <label :class="labelStyling + ' pb-2'">
                        High
                    </label>
                    <slot name="weather-high"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <label :class="labelStyling + ' pb-2'">
                        Low
                    </label>
                    <slot name="weather-low"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <label :class="labelStyling + ' pb-2'">
                        Precipitation
                    </label>
                    <slot name="weather-precipitation"></slot>
                </v-col>
                <v-col cols="5" md="2" offset="1" offset-md="0">
                    <label :class="labelStyling + ' pb-2'">
                        Wind
                    </label>
                    <slot name="weather-wind"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="11" md="8" offset="1" offset-md="2">
                    <label :class="labelStyling + ' pb-2'">
                        General Conditions
                    </label>
                    <slot name="weather-conditions"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Ground Conditions:
                    </label>
                    <slot name="ground-conditions"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Did weather cause a delay today?
                    </label>
                    <slot name="weather-delay"></slot>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Did weather cause unsafe working conditions today?
                    </label>
                    <slot name="working-conditions"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Description of work performed today:
                    </label>
                    <slot name="description"></slot>
                </v-col>
            </v-row>


            <v-divider></v-divider>


            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Any new construction issues?
                    </label>
                    <slot name="were-construction-issues"></slot>
                </v-col>
            </v-row>

            <v-row v-if="wereConstructionIssues">
                <v-col>
                    <label :class="labelStyling">
                        Describe:
                    </label>
                    <slot name="construction-issues"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Any new construction delays?
                    </label>
                    <slot name="were-construction-delays"></slot>
                </v-col>
            </v-row>

            <v-row v-if="wereConstructionDelays">
                <v-col>
                    <label :class="labelStyling">
                        Describe:
                    </label>
                    <slot name="construction-delays"></slot>
                </v-col>
            </v-row>


            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Visitors Today:
                    </label>
                    <slot name="visitors-today"></slot>
                </v-col>
            </v-row>

            <v-row v-if="visitorsToday">
                <v-col cols="3">
                    <label :class="labelStyling">
                        Name:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Company:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Purpose:
                    </label>
                </v-col>
            </v-row>

            <slot name="visitors"></slot>

            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Any Hot Work Performed Today?
                    </label>
                    <slot name="hot-work-today"></slot>
                </v-col>
            </v-row>

            <v-row v-if="hotWorkToday">
                <v-col cols="3">
                    <label :class="labelStyling">
                        Supervisor:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Activity:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Fire Watch:
                    </label>
                </v-col>
            </v-row>

            <slot name="hot-work"></slot>


            <v-divider></v-divider>

            <v-row>
                <v-col>
                    <label :class="labelStyling">
                        Safety Meeting Today:
                    </label>
                    <slot name="safety-meeting-today"></slot>
                </v-col>
            </v-row>

            <v-row v-if="safetyMeetingToday">
                <v-col cols="3">
                    <label :class="labelStyling">
                        Presenter:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Company:
                    </label>
                </v-col>
                <v-col cols="3">
                    <label :class="labelStyling">
                        Topic:
                    </label>
                </v-col>
            </v-row>

            <slot name="presenters"></slot>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="12" sm="5">
                    <label :class="labelStyling">
                        Any Accidents Today?
                    </label>
                    <slot name="accidents-today"></slot>
                </v-col>
                <v-col cols="12" sm="7">
                    <label :class="labelStyling">
                        Description:
                    </label>
                    <slot name="accidents-description"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>


            <v-row>
                <v-col cols="12" sm="5">
                    <label :class="labelStyling">
                        Any Injuries Today?
                    </label>
                    <slot name="injuries-today"></slot>
                </v-col>
                <v-col cols="12" sm="7">
                    <label :class="labelStyling">
                        Description:
                    </label>
                    <slot name="injuries-description"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="12" sm="5">
                    <label :class="labelStyling">
                        Any Near Misses Today?
                    </label>
                    <slot name="near-misses-today"></slot>
                </v-col>
                <v-col cols="12" sm="7">
                    <label :class="labelStyling">
                        Description:
                    </label>
                    <slot name="near-misses-description"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>


            <v-row>
                <v-col cols="12" sm="5">
                    <label :class="labelStyling">
                        Any Safety-Related Reports Filed Today?
                    </label>
                    <slot name="safety-reports-today"></slot>
                </v-col>
                <v-col cols="12" sm="7">
                    <label :class="labelStyling">
                        Description:
                    </label>
                    <slot name="safety-reports-description"></slot>
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
                    <label :class="labelStyling">
                        General Comments:
                    </label>
                    <slot name="general-comments"></slot>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <slot name="action-buttons"></slot>
        </v-expansion-panel-content>
    </v-expansion-panel>
</template>

<script>
    export default {
        props: {
            wereConstructionIssues: {
                type: Boolean,
                default: false
            },
            wereConstructionDelays: {
                type: Boolean,
                default: false
            },
            visitorsToday: {
                type: Boolean,
                default: false
            },
            hotWorkToday: {
                type: Boolean,
                default: false
            },
            safetyMeetingToday: {
                type: Boolean,
                default: false
            }
        },

        computed: {
            labelStyling() {
                return 'subtitle-1 font-weight-bold';
            },
        }
    }
</script>

<style scoped>
    .content-outline {
        border: thin solid hsl(0, 0%, 45.1%);
    }

    .body-outline {
        border: thin solid hsl(0, 0%, 45.1%);
        border-top: 0;
    }
</style>
