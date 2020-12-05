<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="submission.sent_from" :date="submission.created_at"></messaging-header>

        <messaging-body-wrapper>
            <messaging-top-line :user="submission.sent_to" :document="document"></messaging-top-line>


            <v-divider></v-divider>

            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Attached File(s):</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <attached-files :files="submission.files" />
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Comments:</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <div> {{ submission.comment || 'N/A' }}</div>
                </v-col>
            </v-row>

        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "./MessagingHeader";
    import MessagingTopLine from "./MessagingTopLine";
    import MessagingBodyWrapper from "./MessagingBodyWrapper";
    import InteractsWithDates from "../../mixins/InteractsWithDates";
    import AttachedFiles from "../AttachedFiles";

    export default {
        components: {
            MessagingHeader,
            MessagingTopLine,
            MessagingBodyWrapper,
            AttachedFiles
        },

        mixins: [
            InteractsWithDates
        ],

        props: {
            document: {
                type: Object
            },
            submission: {
                type: Object
            }
        }
    }
</script>
