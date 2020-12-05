<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="response.sent_from" :date="response.created_at" :highlight="highlight"></messaging-header>

        <messaging-body-wrapper>
            <v-row class="my-2">
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Status:</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <div>
                        {{ response.status }}
                    </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Attached File(s):</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <attached-files :files="response.files" />
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="5" sm="3" md="2">
                    <span class="font-weight-bold subtitle-1">Comments:</span>
                </v-col>
                <v-col cols="7" sm="9" md="10">
                    <div> {{ response.comment }}</div>
                </v-col>
            </v-row>

        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingHeader from "./MessagingHeader";
    import MessagingBodyWrapper from "./MessagingBodyWrapper";
    import AttachedFiles from "../AttachedFiles";

    export default {
        components: {
            MessagingHeader,
            MessagingBodyWrapper,
            AttachedFiles
        },

        props: {
            response: {
                type: Object,
                required: true
            },
            highlight: {
                type: Boolean,
                default: false
            }
        }
    }
</script>
