<template>
    <v-row justify="center">
        <v-col cols="10">

            <v-row>
                <v-col cols="12">
                    <h4>Select Project Disciplines</h4>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="5">
                    <v-form
                        @submit.prevent="addDiscipline"
                        id="autocomplete"
                        v-model="valid"
                    >
                        <v-autocomplete
                            v-model="searched"
                            outlined
                            dense
                            clearable
                            :rules="required"
                            :items="disciplineList"
                            item-text="label"
                            return-object
                            placeholder="Ex. Mechanical"
                            label="Disciplines"
                        ></v-autocomplete>
                    </v-form>

                </v-col>
                <v-col cols="2">
                    <v-btn
                        type="submit"
                        form="autocomplete"
                        :disabled="!searched"
                        text color="primary darken-2"
                    >
                        Add
                    </v-btn>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-chip
                        v-for="discipline in selected"
                        :key="discipline.id"
                        label
                        close-icon="mdi-close"
                        class="mr-4 mb-2"
                        text-color="black lighten-2"
                        color="light-blue lighten-5"
                        @click:close="removeDiscipline(discipline.id)"
                        close
                    >

                        {{ discipline.label }}
                    </v-chip>
                </v-col>
            </v-row>

            <v-row>
                <v-col cols="12">
                    <v-checkbox
                        color="primary darken-2"
                        label="Save disciplines as default"
                        @change="emitDefaultChange"
                        :input-value="disciplineData.saveDefaults"
                    >
                    </v-checkbox>
                </v-col>
            </v-row>


            <v-row>
                <v-col cols="12">
                    <v-btn
                        color="secondary"
                        text
                        class="mx-0"
                        @click="$emit('back')"
                    >
                        Previous
                    </v-btn>

                    <v-btn
                        class="mx-4"
                        color="primary darken-2"
                        :disabled="selected.length === 0"
                        @click="$emit('next')"
                    >
                        Next
                    </v-btn>
                </v-col>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "Disciplines",
        props: {
            disciplines: {
                type: Array
            },
            disciplineData: {
                type: Object,
                required: true
            },
            defaultDisciplines: {
                type: Array,
            },
            projectManager: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                searched: '',
                valid: true,
                selected: [],
                required: [
                    val => (!!val || this.selected.length !== 0) || 'Must select at least one discipline'
                ],
            }
        },
        created() {
            this.selected = this.disciplineData.selected;

        },
        methods: {
            addDiscipline() {
                this.searched.lead = this.projectManager;
                this.selected.push(this.searched);
                this.searched = null;
            },
            removeDiscipline(id) {
                this.selected = this.selected.filter(discipline => discipline.id !== id);
            },
            emitDefaultChange(value) {
                this.$emit('toggled', value);
            }
        },
        computed: {
            disciplineList() {
                const selectedIds = this.selected.map(({id}) => id);
                return this.disciplines.filter(discipline => !selectedIds.includes(discipline.id)).sort((a, b) => a.sort - b.sort);
            }
        },
        watch: {
            selected: function () {
                this.$emit('select-change', this.selected);
            }
        }
    }
</script>
