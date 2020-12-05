<template>
    <v-expansion-panel class="my-2">
        <messaging-header
            :user="rfi.requested_by"
            :date="rfi.created_at"
        ></messaging-header>

        <messaging-body-wrapper>
            <v-row class="py-2">
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">To:</span>
                </v-col>
                <v-col cols="7" sm="5" md="4">
                    <div>{{ rfi.project_discipline.lead.name }}</div>
                    <div> {{ rfi.project_discipline.lead.organization.name }}</div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">RFI No.:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div>RFI-{{ rfi.identifier}}</div>
                </v-col>
            </v-row>
            <v-row>
                <v-col class="pt-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Discipline:</span>
                </v-col>
                <v-col class="pt-0" cols="7" sm="5" md="4">
                    <div> {{ rfi.project_discipline.discipline.abbreviation }} </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Drawing No.:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div> {{ rfi.drawing_number || 'N/A'}} </div>
                </v-col>
            </v-row>
            <v-row>
                <v-col class="pt-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Specification No.:</span>
                </v-col>
                <v-col class="pt-0" cols="7" sm="5" md="4">
                    <div> {{ rfi.specification_number || 'N/A' }} </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Subject:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div>{{ rfi.subject }}</div>
                </v-col>
            </v-row>
            <v-row>
                <v-col class="pt-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Due Date:</span>
                </v-col>
                <v-col class="pt-0" cols="7" sm="5" md="4">
                    <div :class="[{'red--text': !rfi.isFinalized && isPastDue(rfi.due_date)}]">
                        {{ formatDate(rfi.due_date) }}
                    </div>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="12" lg="2">
                    <span class="font-weight-bold subtitle-1">Attached File(s):</span>
                </v-col>
                <v-col cols="12" lg="10">
                    <attached-files :files="rfi.files" />
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12" lg="2">
                    <span class="font-weight-bold subtitle-1">Question:</span>
                </v-col>
                <v-col cols="12" lg="10">
                    <div> {{ rfi.question }}</div>
                </v-col>
            </v-row>

        </messaging-body-wrapper>

    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "../Documentation/MessagingHeader";
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import AttachedFiles from "../AttachedFiles";
    import InteractsWithDates from "../../mixins/InteractsWithDates";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            AttachedFiles
        },

        mixins: [
            InteractsWithDates
        ],

        props: {
            rfi: {
                type: Object,
                required: true
            }
        },

        methods: {
            isPastDue(dueDate) {
                return new Date() > new Date(dueDate);
            },
        }
    }
</script>
