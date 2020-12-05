<template>
    <div class="pa-2 px-md-8 pt-md-8">
        <v-row>
            <v-col md="6" lg="4">
                <span
                        class="font-weight-medium py-2"
                        :class="selectedProjectImport? 'disabled-text-color':''"
                >
                    Assign To
                </span>
                <v-select
                        class="my-4"
                        :items="guests"
                        :item-text="label"
                        hide-details
                        return-object
                        outlined
                        dense
                        placeholder="Guest..."
                        :value="selectedGuest"
                        @change="emitSelected"
                        :disabled = "selectedProjectImport"
                >
                </v-select>
            </v-col>

            <v-col md="0" lg="4">
            </v-col>

            <v-col md="6" lg="4">

                <v-row class="project-import-row">
                    <v-checkbox
                            color="blue darken-5"
                            class="mt-0"
                            :value="selectedProjectImport"
                            @change="emitProjectSelectedAllow"
                    ></v-checkbox>
                    <span class="font-weight-medium py-1">Copy From Another Project</span>
                    <v-tooltip content-class="summary-tooltip" bottom>
                        <template v-slot:activator="{ on }">
                            <v-icon v-on="on" class="ml-2 project-import-summary">mdi-information-outline</v-icon>
                        </template>
                        <project-import-summary-tooltip />
                    </v-tooltip>
                </v-row>

                <v-select
                        class="my-4"
                        :items="projectsList"
                        :item-text="labelProject"
                        hide-details
                        return-object
                        outlined
                        dense
                        :disabled = "selectedProjectImport == false"
                        placeholder="Select Project to Copy"
                        @change="emitProjectSelected"
                >
                </v-select>
            </v-col>
        </v-row>
    </div>
</template>

<script>
    import ProjectImportSummaryTooltip from "../Shared/ProjectImportSummaryTooltip";
    export default {
        components: {
            ProjectImportSummaryTooltip
        },
        props: {
            guests: {
                type: Array,
                required: true
            },
            selectedGuest: {
                type: Object,
                required: true
            },
            projectsList:{
                type: Array,
                required: true
            },
            selectedProjectImport:{
                type: Boolean,
                required: true
            }
        },

        methods: {
            label(guest) {
                return `${guest.first_name} ${guest.last_name}`
            },

            labelProject(project) {
                return `${project.name}, ${project.client_name}`
            },

            emitSelected(guest) {
                this.$emit('select', guest);
            },
            emitProjectSelected(project) {
                this.$emit('selectProject', project);
            },
            emitProjectSelectedAllow(status) {
                if (status)
                    this.$emit('selectProjectAllow', true);
                else
                    this.$emit('selectProjectAllow', false);

            }
        }
    }
</script>
<style scoped>
    .summary-tooltip {
        background-color: #F5F5F5;
        color: black;
        border-radius: 10px;
        border: black 2px solid;
        opacity: 1 !important;
    }
    .project-import-row{
        height:24px;
        margin-left: -30px;
    }
    .project-import-summary{
        height:30px
    }

</style>