<template>
    <v-dialog :value="dialog" max-width="300" persistent>
        <v-card>
            <v-card-title>
                Select Form
            </v-card-title>

            <v-card-text class="p-4">
                <v-form v-model="valid" ref="form">
                    <v-select
                        v-model="selectedForm"
                        :items="items"
                        :rules="[
                            (val) => !!val || 'Form Selection is required'
                        ]"
                        item-text="label"
                        item-value="value"
                        label="Form Type"
                        dense
                        solo
                    ></v-select>
                </v-form>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn text @click="$emit('close')">Cancel</v-btn>
                <v-btn color="primary darken-1" text @click="select">Select</v-btn>
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
            }
        },

        data() {
            return {
                valid: false,
                selectedForm: null,
                items: [
                    {
                        label: 'Incident Report',
                        value: 'incident-report'
                    },
                    {
                        label: 'Daily Log',
                        value: 'daily-log'
                    }
                ]
            }
        },

        methods: {
            select() {
                if (this.$refs.form.validate()) {
                    this.$emit('select', this.selectedForm);
                }
            }
        }
    }
</script>

