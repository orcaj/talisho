<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title>
                Designate New Due Date
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="handleClose">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <v-card-text>
            <v-form v-model="valid" ref="form">
                <div class="mx-2 my-4 mx-sm-4">
                    <v-row justify="center">
                        <v-col cols="6">
                            <span class="font-weight-medium py-2">Due Date</span>
                            <v-menu
                                ref="menu"
                                v-model="menu"
                                :close-on-content-click="false"
                                transition="scale-transition"
                                offset-y
                                max-width="290px"
                                min-width="290px"
                            >
                                <template v-slot:activator="{ on }">
                                    <v-text-field
                                        v-model="dateFormatted"
                                        class="my-4"
                                        readonly
                                        outlined
                                        dense
                                        @blur="date = parseDate(dateFormatted)"
                                        v-on="on"
                                    >
                                        <template v-slot:append>
                                            <v-icon v-on="on">mdi-calendar</v-icon>
                                        </template>
                                    </v-text-field>
                                </template>
                                <v-date-picker
                                    v-model="date"
                                    no-title
                                    @input="menu = false"
                                    :min="minDate"
                                ></v-date-picker>
                            </v-menu>

                        </v-col>
                    </v-row>
                </div>
            </v-form>

            <v-row justify="center">
                <v-col cols="4">
                    <v-btn
                        class="ml-0 mr-4 mb-2 mx-sm-4 mb-sm-0 no-underline"
                        min-width="150px"
                        outlined
                        color="blue darken-5"
                        @click="handleClose"
                    >
                        Cancel
                    </v-btn>
                </v-col>
                <v-col cols="4">
                    <v-btn
                        class="ml-0 mb-2 mx-sm-0 mb-sm-0 white--text no-underline"
                        color="blue darken-5"
                        min-width="150px"
                        @click="updateDueDate"
                    >
                        Save Changes
                    </v-btn>
                </v-col>
            </v-row>

        </v-card-text>

        <!--     Confirm Exit Modal      -->
        <confirmation-modal
            :dialog="dialog"
            @close="dialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
import ConfirmationModal from "./ConfirmationModal";
import InteractsWithDates from "../../mixins/InteractsWithDates";

export default {
    components: {
        ConfirmationModal
    },

    mixins: [
        InteractsWithDates
    ],

    props: {
        documentationIds: {
            type: Array,
            required: true
        }
    },

    data: vm => {
        const today = new Date();
        return {
            dialog: false,
            date: new Date(today).toISOString().substr(0, 10),
            dateFormatted: vm.formatDate(new Date(today).toISOString().substr(0, 10)),
            menu: false,
            valid: false,
        }
    },

    computed: {
        minDate() {
            return new Date().toISOString();
        }
    },

    methods: {
        handleClose() {
            this.dialog = true;
        },
        updateDueDate() {
            if (this.$refs.form.validate()) {
                this.$inertia.put(this.route('documentation.due-date.multiple'), {
                    'due_date': this.date,
                    'documentation_ids': this.documentationIds
                });

                this.$emit('submitted');
            }
        }
    },

    watch: {
        date(val) {
            this.dateFormatted = this.formatDate(this.date)
        },
    },

}
</script>
