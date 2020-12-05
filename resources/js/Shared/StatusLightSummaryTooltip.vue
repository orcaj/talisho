<template>
    <div style="max-width: 400px">
        <div style="text-decoration: underline; font-weight: bold" class="mb-2">Status Light Legend</div>
        <p>
            Taliho utilizes colored lights to indicate the real-time status of overall project performance and project disciplines,
            as well as the status of project documents, communications and liability exposure. In general, the colored status
            lights are utilized to indicate the following:
        </p>

        <div class="ml-4 my-1">
            <v-row>
                <v-col cols="1">
                    <status-light
                        :should-show-tooltip="false"
                        color="green"
                    />
                </v-col>
                <v-col cols="4">
                    <span>Good status</span>
                </v-col>
                <v-col class="px-0 mx-0" cols="7">
                    <span>No additional action is required from you at this time.</span>
                </v-col>
            </v-row>
        </div>

        <div class="ml-4 my-1">
            <v-row>
                <v-col cols="1">
                    <status-light
                        :should-show-tooltip="false"
                        color="yellow"
                    />
                </v-col>
                <v-col cols="4">
                    <span>Warning status</span>
                </v-col>
                <v-col class="px-0 mx-0" cols="7">
                    <span>A response is required from you prior to the respective due date.</span>
                </v-col>
            </v-row>
        </div>

        <div class="ml-4 my-1">
            <v-row>
                <v-col cols="1">
                    <status-light
                        :should-show-tooltip="false"
                        color="red"
                    />
                </v-col>
                <v-col cols="4">
                    <span>Late status</span>
                </v-col>
                <v-col class="px-0 mx-0" cols="7">
                    <span>The respective due date has passed and a response from you is required.</span>
                </v-col>
            </v-row>
        </div>

        <div class="ml-4 my-1">
            <v-row>
                <v-col cols="1">
                    <status-light
                        :should-show-tooltip="false"
                        color="white"
                    />
                </v-col>
                <v-col cols="4">
                    <span>Null status</span>
                </v-col>
                <v-col class="px-0 mx-0" cols="7">
                    <span>There is no action required from you for the foreseeable future.</span>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
import StatusLight from "./StatusLight";
export default {
    name: "StatusLightSummaryTooltip",
    components: {StatusLight},
}
</script>

