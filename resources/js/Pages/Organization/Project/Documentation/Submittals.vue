<template>
    <v-form v-model="valid" ref="form">
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
                                :disabled="selectedProjectImport"
                            ></v-date-picker>
                        </v-menu>

                    </v-col>
                </div>
            </div>

            <div class="outline">
                <div class="pa-2 pa-md-8">
                    <v-col>
                        <v-row>
                            <v-col cols="12" sm="5" md="4" lg="3" class="pb-0">
                                <span class="font-weight-medium" :class="selectedProjectImport? 'disabled-text-color' : ''" >Filter by Division:</span>
                                <v-select
                                    v-model="filter"
                                    clearable
                                    outlined
                                    dense
                                    hide-details
                                    item-value="id"
                                    :items="divisions"
                                    placeholder="Choose..."
                                    class="py-2 my-4"
                                    :disabled="selectedProjectImport"
                                >
                                    <template v-slot:item="{ item }">
                                        <div>{{ formatDivision(item) }}</div>
                                    </template>
                                    <template v-slot:selection="{ item }">
                                        <div> {{ formatSelected(item) }}</div>
                                    </template>
                                </v-select>
                            </v-col>
                            <v-col cols="12" sm="7" md="8" lg="9" class="pb-0">
                                <csi-search
                                    ref="csiSearch"
                                    :rules="[
                                        (val) => (!!val || (!!customSpecName && !!customSpecCode)) || 'Must either select standard specification, custom specification, or enter information for new custom specification',
                                        (val) => ((selectedSearchCsis.length === 0 && !!customSpecCode && !!customSpecName) || (selectedSearchCsis.length > 0 && !customSpecCode && !customSpecName)) || 'Clear custom specification entries below to submit: cannot select standard specification and custom specification simultaneously.',
                                    ]"
                                    :csi-list="listForAutocomplete"
                                    :multiple="false"
                                    :display-text="{
                                        title: 'Search by Spec ID or Name',
                                        placeholder: 'Search...'
                                    }"
                                    @input="updatedCsiSearchList"
                                    @selected="updateSelectedSearchCsi"
                                    :ids-of-documents-already-included-in-project="idsOfCsisInProject"
                                    @clear="selectedSearchCsis = []"
                                    :disabled="selectedProjectImport"
                                ></csi-search>
                            </v-col>
                        </v-row>
                        <custom-specification
                            ref="customSpec"
                            :rules="[
                                (val) => (selectedSearchCsis.length > 0 || (!!customSpecName && !!customSpecCode)) || 'Must either select standard specification, custom specification, or enter information for new custom specification',
                                (val) => ((!!customSpecCode && !!customSpecName && selectedSearchCsis.length === 0) || (selectedSearchCsis.length > 0 && !customSpecName && !customSpecCode)) || 'Clear above specification to submit: cannot select standard specification and custom specification simultaneously.'
                            ]"
                            :custom-spec-route="route('custom-specifications.submittals', [$page.organization.id])"
                            @update-name="updateName"
                            @update-code="updateCode"
                            @clear="clearCustom"
                            :disabled="selectedProjectImport"
                        />
                    </v-col>
                </div>
            </div>

            <div class="outline">
                <document-quick-list
                    title="Associated Documents"
                    :quick-list="quickList"
                    :selected="selectedQuickListCsis"
                    @selected="updateSelectedQuickList"
                    :disabled="selectedProjectImport"
                ></document-quick-list>
            </div>

            <div class="outline">
                <div class="pa-2 pa-md-8">
                    <v-col>
                        <v-row>
                            <v-btn
                                    @click="submitAndRefreshSelectedCsis"
                                    color="blue darken-5"
                                    class="ml-4 mb-2 mx-sm-0 mb-sm-0 white--text"
                                    :disabled = "selectedProjectImport"
                            >Assign and New</v-btn>
                            <v-btn @click="submitAndClose" outlined color="blue darken-5" class=" ml-4 mr-0 mb-2 mx-sm-4 mb-sm-0">Assign and Close</v-btn>
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
            There was a problem searching for Specs.
        </v-snackbar>
    </v-form>
</template>

<script>
    import CsiSearch from "../../../../Shared/CsiSearch";
    import DocumentQuickList from "../../../../Shared/DocumentQuickList";
    import AssignUser from "../../../../Shared/AssignUser";
    import InteractsWithAssignUser from "../../../../mixins/InteractsWithAssignUser";
    import InteractsWithCsiSelections from "../../../../mixins/InteractsWithCsiSelections";
    import HandlesDocumentationDateSelection from "../../../../mixins/HandlesDocumentationDateSelection";
    import CustomSpecification from "../../../../Shared/Documentation/CustomSpecification";
    import InteractsWithCustomSpecifications from "../../../../mixins/InteractsWithCustomSpecifications";
    import InteractsWithProjectImport from "../../../../mixins/InteractsWithProjectImport";

    export default {
        components: {
            CsiSearch,
            DocumentQuickList,
            AssignUser,
            CustomSpecification
        },

        mixins: [
            InteractsWithAssignUser,
            InteractsWithCsiSelections,
            HandlesDocumentationDateSelection,
            InteractsWithCustomSpecifications,
            InteractsWithProjectImport
        ],

        props: {
            divisions: {
                type: Array
            },
            projectsList: {
                type: Array
            },
        },

        data() {
            return {
                valid: true,
            }
        },

        computed: {
            submitRoute() {
                return this.route('documentation.submittal.store', [this.projectDisciplineWithTeam.id, this.selectedGuest.id]);
            },

            submitImportRoute() {
                return this.route('documentation.submittal.import', [this.projectDisciplineWithTeam.id]);
            },
        },

        methods: {
            formatDivision(division) {
                return `${division.name} (${this.formatDivisionCode(division.code)})`;
            },
            formatSelected(division) {
                return `${this.formatDivisionName(division.name)} (${this.formatDivisionCode(division.code)})`;
            },
            formatDivisionName(name) {
                return name.length >= 13 ? name.substr(0, 13) + '...' : name;
            },
            formatDivisionCode(code) {
                return code.substr(0, 2);
            },
            clearCustom() {
                this.customSpecName = '';
                this.customSpecCode = '';
                this.$refs.form.validate();
            },
            submitAndRefreshSelectedCsis() {
                if (this.selectedProjectImport){
                    if(this.selectedImporetedProject != null)
                    {
                        this.submit(true);
                    }
                }
                else{
                    if (this.$refs.form.validate()) {
                        this.submit(true);
                        this.$refs.form.resetValidation();

                        this.selectedSearchCsis = [];
                        this.selectedQuickListCsis = [];
                        this.customSpecName = '';
                        this.customSpecCode = '';
                        this.filter = null;
                        this.$refs.csiSearch.clearSelected();
                        this.$refs.customSpec.clearSelected();

                        this.$emit('scroll-to-top');
                    }
                }
            },
            submitAndClose() {
                if (this.selectedProjectImport){
                    if(this.selectedImporetedProject != null)
                    {
                        this.submit(true);
                    }
                }
                else{
                    if (this.$refs.form.validate()) {
                        this.submit();
                    }
                }
            },

            submit(preserveState = false) {
                if (this.selectedProjectImport){
                    this.$inertia.post(this.submitImportRoute, {
                        old_project_id: this.selectedImporetedProject
                    }, {
                        preserveState: preserveState
                    });
                }
                else{
                    this.$inertia.post(this.submitRoute, {
                        submittal: this.selectedSearchCsis[0]
                            ?
                            {
                                code: this.selectedSearchCsis[0].code,
                                name: this.selectedSearchCsis[0].name
                            }
                            :
                            {
                                code: this.customSpecCode,
                                name: this.customSpecName
                            },
                        associated_document_ids: this.selectedQuickListCsis.map(({id}) => id),
                        due_date: this.date
                    }, {
                        preserveState: preserveState
                    });
                }
            }
        },

        watch: {
            customSpecName(val) {
                if (!!val) {
                    this.$refs.form.validate();
                }
            },
            customSpecCode(val) {
                if (!!val) {
                    this.$refs.form.validate();
                }
            },
            selectedSearchCsis(val) {
                if (!!val) {
                    this.$refs.form.validate();
                }
            }
        }
    }
</script>

<style scoped>
    .outline {
        border: thin solid rgba(0, 0, 0, .12)
    }
</style>
