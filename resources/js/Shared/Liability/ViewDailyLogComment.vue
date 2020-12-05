<template>
    <v-expansion-panel class="my-2">
        <messaging-header :user="comment.commented_by" :date="comment.created_at" />

        <messaging-body-wrapper>
            <v-row class="pt-4">
                <v-col>
                    <label class="font-weight-bold subtitle-1">
                        Additional Comments:
                    </label>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div> {{ comment.comment }} </div>
                </v-col>
            </v-row>

            <v-row>
                <v-col>
                    <div class="subtitle-1 font-weight-bold pb-2">Files: </div>
                    <attached-files :files="comment.files" />
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
            MessagingBodyWrapper,
            MessagingHeader,
            AttachedFiles
        },

        props: {
            comment: {
                type: Object,
                required: true
            }
        }
    }
</script>

