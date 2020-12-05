<template>
    <v-expansion-panel class="my-2">

        <messaging-header
            :user="directive.sent_by"
            :date="directive.created_at"
        ></messaging-header>

        <messaging-body-wrapper>
            <v-row class="py-2">
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">To:</span>
                </v-col>
                <v-col cols="7" sm="5" md="4">
                    <div v-for="guest in directive.guests">{{ guest.name }} - {{ guest.organization.name }}</div>
                </v-col>
            </v-row>
            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Directive No.:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div>CD-{{ directive.identifier}}</div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pt-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Discipline:</span>
                </v-col>
                <v-col class="pt-0" cols="7" sm="5" md="4">
                    <div> {{ directive.project_discipline.discipline.abbreviation }}</div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Drawing No.:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div>{{ directive.drawing_number || 'N/A' }}</div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pt-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Specification No.:</span>
                </v-col>
                <v-col class="pt-0" cols="7" sm="5" md="4">
                    <div>{{ directive.specification_number || 'N/A' }}</div>
                </v-col>
            </v-row>

            <v-row>
                <v-col class="pb-0" cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Subject:</span>
                </v-col>
                <v-col class="pb-0" cols="7" sm="5" md="4">
                    <div>{{ directive.subject }}</div>
                </v-col>
            </v-row>

            <v-divider></v-divider>

            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Attached File(s):</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <attached-files :files="directive.files"/>
                </v-col>
            </v-row>
            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Directive:</span>
                </v-col>

                <v-col cols="7" sm="9" md="10">
                    <div> {{ directive.directive }}</div>
                </v-col>
            </v-row>
        </messaging-body-wrapper>

    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "../Documentation/MessagingHeader";
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import AttachedFiles from "../AttachedFiles";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            AttachedFiles
        },

        props: {
            directive: {
                type: Object,
                required: true
            }
        }
    }
</script>
