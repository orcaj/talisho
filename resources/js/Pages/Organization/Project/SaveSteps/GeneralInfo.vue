<template>
    <v-form v-model="valid" id="general-info">
        <v-row justify="center">
            <v-col cols="5">
                <h4 class="pb-4">General Project Information</h4>

                <v-text-field
                    v-model="generalInfo.clientName"
                    class="pb-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Client Name', 255)"
                    label="Client Name"
                    placeholder="Client Name"
                ></v-text-field>

                <v-text-field
                    v-model="generalInfo.projectName"
                    class="py-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Project Name', 255)"
                    label="Project Name"
                    placeholder="Project Name"
                ></v-text-field>

                <v-text-field
                    v-model="generalInfo.street"
                    class="py-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('Street', 255)"
                    label="Street"
                    placeholder="Street"
                ></v-text-field>

                <v-text-field
                    v-model="generalInfo.city"
                    class="py-2"
                    outlined
                    dense
                    :rules="requireWithMaxLength('City', 255)"
                    label="City"
                    placeholder="City"
                ></v-text-field>

                <v-row>
                    <v-col cols="6">
                        <v-select
                            :items="statesObj"
                            v-model="generalInfo.state"
                            :rules="requireWithMaxLength('State', 255)"
                            item-text="name"
                            item-value="id"
                            outlined
                            dense
                            label="State"
                            placeholder="State"
                        ></v-select>

                    </v-col>
                    <v-col cols="6">
                        <v-text-field
                            v-model="generalInfo.zip"
                            outlined
                            dense
                            :rules="requireWithMaxLength('Zip Code', 255)"
                            label="Zip Code"
                            placeholder="Zip Code"
                        ></v-text-field>
                    </v-col>
                </v-row>

            </v-col>
            <v-col cols="5">
                <v-textarea
                    v-model="generalInfo.description"
                    class="pt-12"
                    outlined
                    no-resize
                    rows="15"
                    :rules="requireWithMaxLength('Description', 500)"
                    placeholder="Project description..."
                    :label="`Description (${remainingChars} characters remaining)`"
                ></v-textarea>
            </v-col>
        </v-row>

        <v-row justify="center">
            <v-col cols="10">
                <inertia-link
                    class="no-underline"
                    :href="route('organizations.projects.index', {organization: $page.auth.user.organization.id})">
                    <v-btn color="secondary" text>
                        Cancel
                    </v-btn>
                </inertia-link>

                <v-btn
                    class="mx-4"
                    color="primary darken-2"
                    :disabled="!valid"
                    @click="$emit('next')"
                >
                    {{ editing ? 'Submit' : 'Next'}}
                </v-btn>
            </v-col>
        </v-row>
    </v-form>
</template>

<script>
    import states from '../../../../../data/states'

    export default {
        name: "GeneralInfo",
        props: {
            generalInfo: {
                type: Object
            },
            editing: {
                type: Boolean,
                default: false
            }
        },
        data() {
            return {
                valid: false
            }
        },
        methods: {
            requireWithMaxLength(field, length) {
                return [
                    val => !!val || `${field} is required.`,
                    val => (val || '').length <= length || `${field} must be ${length} characters or less`,
                ]
            },
        },
        computed: {
            remainingChars() {
                const length = 500 - this.generalInfo.description.length;
                return length >= 0 ? length : 0;
            },
            statesObj() {
                return Object.entries(states).map(([key, value]) => ({id: key, name: value}));
            },
        },
        watch: {
            valid: function (newValue) {
                this.$emit('valid', newValue);
            }
        }
    }
</script>
