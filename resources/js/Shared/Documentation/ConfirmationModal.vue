<template>
    <v-dialog :value="dialog" persistent max-width="300" @input="close" @keydown.esc="close">
        <v-card>
            <!--    TODO would be a nice to have to remove default text here and implement action specific wording for each use of this modal    -->
            <v-card-title class="headline">
                <slot name="title">
                    Discard Changes?
                </slot>
            </v-card-title>
            <v-card-text>
                <slot>
                    Leaving this screen will delete any information that has been entered. Are you sure you want to leave?
                </slot>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn class="no-underline" text @click="close">Cancel</v-btn>
                <v-btn class="no-underline" color="red darken-1" text @click="confirm">
                    <slot name="action-text">
                        Leave
                    </slot>
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: {
            dialog: {
                type: Boolean,
                required: true
            },
        },
        methods: {
            close() {
                this.$emit('close');
            },
            confirm() {
                this.$emit('confirm');
            }
        }
    }
</script>
