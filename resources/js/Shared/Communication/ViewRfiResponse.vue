<template>
    <v-expansion-panel class="my-2">
        <messaging-header
            :user="rfi.response.responded_by"
            :date="rfi.response.created_at"
        ></messaging-header>

        <messaging-body-wrapper>
            <v-row class="pt-8">
                <v-col cols="12" lg="2">
                    <span class="font-weight-bold subtitle-1">Attached File(s):</span>
                </v-col>
                <v-col cols="12" lg="10">
                    <attached-files :files="rfi.response.files" />
                </v-col>
            </v-row>

            <v-row class="pb-4">
                <v-col cols="12" lg="2">
                    <span class="font-weight-bold subtitle-1">Question:</span>
                </v-col>
                <v-col cols="12" lg="10">
                    <div> {{ rfi.response.response }}</div>
                </v-col>
            </v-row>

        </messaging-body-wrapper>
    </v-expansion-panel>
</template>

<script>
    import MessagingBodyWrapper from "../Documentation/MessagingBodyWrapper";
    import MessagingHeader from "../Documentation/MessagingHeader";
    import AttachedFiles from "../AttachedFiles";

    export default {
        components: {
            MessagingBodyWrapper,
            MessagingHeader,
            AttachedFiles
        },

        props: {
            rfi: {
                type: Object,
                required: true
            }
        },

    }
</script>
