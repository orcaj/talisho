<template>
    <v-form v-model="valid">
        <div class="my-4 outline">
            <div class="outline">
                <assign-user
                    :guests="teamAndLead"
                    :projectsList="projectsList"
                    :selected-guest="selectedGuest"
                    :selectedProjectImport = "selectedProjectImport"
                    @select="setSelectedGuest"
                    @selectProject="setSelectedProject"
                    @selectProjectAllow="setSelectedProjectAllow"
                ></assign-user>

                <div class="pb-2 px-2 px-md-8 pb-md-8">
                    <v-col md="6" lg="4">
                        <span
                                class="font-weight-medium py-2"
                                :class="selectedProjectImport? 'disabled-text-color' : ''"
                        >
                            Due Date
                        </span>
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
                                    placeholder="TBD By Assignee"
                                    class="my-4"
                                    readonly
                                    outlined
                                    dense
                                    @blur="date = parseDate(dateFormatted)"
                                    v-on="on"
                                    :class="selectedProjectImport? 'disabled-text-color' : ''"
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
                </div>
            </div>
            <div class="outline">
                <document-quick-list
                    title="Quick Selection List"
                    :quick-list="quickList"
                    :selected="selectedQuickListCsis"
                    @selected="updateSelectedQuickList"
                    :disabled = "selectedProjectImport"
                ></document-quick-list>
            </div>
            <div class="outline">
                <div class="pa-2 pa-md-8">
                    <v-row>
                        <v-col>
                            <csi-search
                                :csi-list="listForAutocomplete"
                                :display-text="{
                                title: searchTitle,
                                placeholder: placeholderText
                            }"
                                @input="updatedCsiSearchList"
                                @selected="updateSelectedSearchCsi"
                                :ids-of-documents-already-included-in-project="idsOfCsisInProject"
                                :disabled ="selectedProjectImport"
                            />

                            <custom-specification
                                multiple
                                :selected="selectedExistingCustomSpecifications"
                                :custom-spec-route="customSpecificationRoute"
                                @selected="updateSelectedSpecifications"
                                @add-custom-specification="addCustomSpecification"
                                :disabled ="selectedProjectImport"
                            />

                            <csi-selections
                                :selected="selectedSearchCsis"
                                :new-specifications="newCustomSpecifications"
                                :existing-specifications="selectedExistingCustomSpecifications"
                                @remove-csi="removeSearchedCsi"
                                @remove-new-specification="removeNewCustomSpecification"
                                @remove-existing-specification="removeExistingCustomSpecification"
                            />
                        </v-col>
                    </v-row>
                </div>
            </div>
            <div class="outline">
                <div class="pa-2 pa-md-8">
                    <v-col>
                        <v-row>
                            <v-btn :disabled="disableButton || selectedProjectImport" @click="submitAndNew" color="blue darken-5"
                                   class="ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0 white--text">
                                Assign and New
                            </v-btn>
                            <v-btn
                                    :disabled="!((selectedProjectImport && selectedImporetedProject != null) || !disableButton)"
                                    @click="submitAndClose" outlined color="blue darken-5"
                                    class="ml-4 mb-2 mx-sm-0 mb-sm-0"
                            >
                                Assign and Close
                            </v-btn>
                        </v-row>
                    </v-col>
                </div>
            </div>
        </div>

        <v-snackbar
            v-model="error"
            color="red"
            bottom
            :timeout="2000"
        >
            There was a problem searching for CSIs.
        </v-snackbar>
    </v-form>
</template>

<script>
import AssignUser from "../../../../Shared/AssignUser";
import DocumentQuickList from "../../../../Shared/DocumentQuickList";
import CsiSearch from "../../../../Shared/CsiSearch";
import CsiSelections from "../../../../Shared/CsiSelections";
import InteractsWithAssignUser from "../../../../mixins/InteractsWithAssignUser";
import InteractsWithProjectImport from "../../../../mixins/InteractsWithProjectImport";
import InteractsWithCsiSelections from "../../../../mixins/InteractsWithCsiSelections";
import HandlesDocumentationDateSelection from "../../../../mixins/HandlesDocumentationDateSelection";
import CustomSpecification from "../../../../Shared/Documentation/CustomSpecification";
import InteractsWithCustomSpecifications from "../../../../mixins/InteractsWithCustomSpecifications";

export default {
    components: {
        CustomSpecification,
        AssignUser,
        DocumentQuickList,
        CsiSearch,
        CsiSelections
    },

    props: {
        searchTitle: {
            type: String
        },
        placeholderText: {
            type: String
        },
        projectsList: {
            type: Array
        },
        submitRouteName: {
            type: String
        },
        submitImportRouteName: {
            type: String
        },
        customSpecificationRoute: {
            type: String,
            required: true
        }
    },

    data() {
        return {
            valid: true,
            newCustomSpecifications: [],
            selectedExistingCustomSpecifications: []
        }
    },

    mixins: [
        InteractsWithAssignUser,
        InteractsWithCsiSelections,
        InteractsWithProjectImport,
        HandlesDocumentationDateSelection,
        InteractsWithCustomSpecifications
    ],

    computed: {
        submitRoute() {
            return this.route(this.submitRouteName, [this.projectDisciplineWithTeam.id, this.selectedGuest.id]);
        },
        submitImportRoute() {
            return this.route(this.submitImportRouteName, [this.projectDisciplineWithTeam.id]);
        },
        disableButton() {
            return !this.selectedGuest || (this.selectedQuickListCsis.length === 0 && this.selectedSearchCsis.length === 0 && this.newCustomSpecifications.length === 0 && this.selectedExistingCustomSpecifications.length === 0);
        }
    },

    methods: {
        submitAndNew() {
            this.submit(true);
            this.selectedSearchCsis = [];
            this.selectedQuickListCsis = [];
            this.newCustomSpecifications = [];
            this.selectedExistingCustomSpecifications = [];
            this.customSpecCode = '';
            this.customSpecName = '';

            this.$emit('scroll-to-top');
        },
        submitAndClose() {
            this.submit();
        },
        submit(preserveState = false) {
            debugger
            if (this.selectedProjectImport){
                this.$inertia.post(this.submitImportRoute, {
                    old_project_id: this.selectedImporetedProject
                }, {
                    preserveState: preserveState
                });
            }
            else{
                this.$inertia.post(this.submitRoute, {
                    specifications: this.selectedSearchCsis.concat(this.selectedQuickListCsis).concat(this.newCustomSpecifications).concat(this.selectedExistingCustomSpecifications),
                    due_date: this.date
                }, {
                    preserveState: preserveState
                });
            }
        },
        addCustomSpecification(spec) {
            this.newCustomSpecifications.push(spec);
        },
        updateSelectedSpecifications(spec) {
            this.selectedExistingCustomSpecifications.push(spec);
        },
        removeExistingCustomSpecification(removed) {
            this.selectedExistingCustomSpecifications = this.selectedExistingCustomSpecifications.filter(spec => spec.id !== removed.id);
        },
        removeNewCustomSpecification(removed) {
            this.newCustomSpecifications = this.newCustomSpecifications.filter(spec => spec.name !== removed.name);
        }
    },
}
</script>

<style scoped>
.outline {
    border: thin solid rgba(0, 0, 0, .12)
}
</style>
<style>
.disabled-text-color{
    color: grey!important;
    pointer-events: none;
}
</style>
