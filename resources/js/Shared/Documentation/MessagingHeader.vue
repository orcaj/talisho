<template>
    <v-expansion-panel-header :class="['header-outline', highlight ? 'header-background' : '']">
        <v-row>
            <v-col cols="5" sm="3" md="2">
                <span class="font-weight-bold subtitle-1">From:</span>
            </v-col>
            <v-col cols="7" sm="4" md="4">
                <div class="pb-3">{{ user.name }}</div>
                <div> {{ user.organization.name }}</div>
            </v-col>
            <v-col cols="5" class="d-sm-none">
                <span class="font-weight-bold subtitle-1">
                    Date:
                </span>
            </v-col>
            <v-col cols="7" sm="3" md="3" offset="0" offset-sm="1" offset-md="2" align-self="center">
                <div>{{ formatDate(date) }}</div>
            </v-col>
        </v-row>
    </v-expansion-panel-header>
</template>

<script>
    import InteractsWithDates from "../../mixins/InteractsWithDates";

    export default {
        mixins: [
            InteractsWithDates
        ],

        props: {
            user: {
                type: Object
            },
            date: {
                type: String
            },
            highlight: {
                type: Boolean,
                default: false
            }
        }
    }
</script>

<style scoped>
    .header-outline {
        border: thin solid hsl(0, 0%, 45.1%);
    }

    .header-background {
        background-color: #E6EFFF;
    }
</style>
