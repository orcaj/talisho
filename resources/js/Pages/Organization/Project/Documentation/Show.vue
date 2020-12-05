<template>
    <v-row justify="center">
        <v-col class="mx-8">
            <v-card tile>
                <v-card-title class="blue darken-5 py-2">
                    <v-row align="center" justify="space-between" class="mx-2 my-0">

                        <v-col cols="12" lg="9">
                            <inertia-link
                                :href="route('organizations.projects.documentation.index', [$page.organization.id, $page.project.id])"
                                class="action-text pr-1 no-underline"
                            >
                                <span class="action-text">
                                    Documentation
                                </span>
                            </inertia-link>
                            <span class="action-text">
                                / {{ documentTypeTitle }}
                            </span>
                        </v-col>

                        <v-col v-if="!onlyOneDiscipline" cols="12" lg="3">
                            <discipline-selector
                                :project-disciplines="disciplines"
                                @updated="clearSelectedDocuments"
                            />
                        </v-col>
                    </v-row>

                </v-card-title>

                <div class="py-4">
                    <v-data-table
                        ref="table"
                        class="px-2"
                        hide-default-footer
                        disable-pagination
                        :headers="table.headers"
                        :search="search"
                        :items="documentsForSelectedDiscipline"
                        show-expand
                        @update:expanded="handleExpand"
                        calculate-widths
                    >
                        <template v-slot:header.assigned="{ header }">
                            <div class="d-inline-flex">
                                <v-checkbox
                                    v-if="isUserDisciplineLead && documentIdsForSelectedDiscipline.length > 0"
                                    :input-value="allSelectedForAssignEdit"
                                    :indeterminate="!!selectedDocumentsForAssignmentEdit.length && ! allSelectedForAssignEdit"
                                    color="blue darken-5"
                                    label=""
                                    hide-details
                                    @click.capture.stop="toggleAllAssigned"
                                >
                                </v-checkbox>
                                <span class="label-text mt-1">{{ header.text }}</span>
                                <v-icon
                                    v-if="isUserDisciplineLead"
                                    small
                                    class="ml-2"
                                    color="blue darken-5"
                                    :disabled="selectedDocumentsForAssignmentEdit.length === 0"
                                    @click.capture.stop.self="editMultipleAssignments"
                                >
                                    mdi-pencil
                                </v-icon>
                            </div>
                        </template>

                        <template v-slot:header.due_date="{ header }">
                            <div class="d-inline-flex">
                                <v-checkbox
                                    v-if="isUserDisciplineLead && documentIdsForSelectedDiscipline.length > 0"
                                    :input-value="allSelectedForDueDateEdit"
                                    :indeterminate="!!selectedDocumentsForDueDateEdit.length && ! allSelectedForDueDateEdit"
                                    color="blue darken-5"
                                    hide-details
                                    @click.capture.stop="toggleAllDueDates"
                                >
                                </v-checkbox>
                                <span class="label-text mt-1">{{ header.text }}</span>
                                <v-icon
                                    v-if="isUserDisciplineLead"
                                    small
                                    class="ml-2"
                                    color="blue darken-5"
                                    :disabled="selectedDocumentsForDueDateEdit.length === 0"
                                    @click.capture.stop.self="editMultipleDueDates"
                                >
                                    mdi-pencil
                                </v-icon>
                            </div>
                        </template>

                        <template v-slot:item.data-table-expand="{ item, isExpanded, expand }">
                            <div v-if="!!item.associated_documents && item.associated_documents.length > 0"
                                 style="min-width: 75px;">
                                <v-icon
                                    v-if="!isExpanded"
                                    @click="expand(true)"
                                >
                                    mdi-chevron-down
                                </v-icon>
                                <v-icon
                                    v-else-if="isExpanded"
                                    @click="expand(false)"
                                >
                                    mdi-chevron-up
                                </v-icon>
                                <v-tooltip bottom>
                                    <template v-slot:activator="{ on }">
                                        <v-icon
                                            v-if="submittalHasAssociatedDocumentNeedingAction(item)"
                                            v-on="on"
                                            dark
                                            color="red"
                                        >
                                            mdi-alert-circle-outline
                                        </v-icon>
                                    </template>
                                    <span>An Associated Document for this Submittal needs attention.</span>
                                </v-tooltip>

                            </div>
                        </template>

                        <template v-slot:expanded-item="{ headers, item }">
                            <td :colspan="headers.length" class="px-0 mx-0">
                                <tr v-for="item in item.associated_documents">
                                    <td :style="subTableWidths[0]"></td>
                                    <td :style="subTableWidths[1]">
                                        <span class="ml-4">{{ item.csi_id }}</span>
                                    </td>
                                    <td :style="subTableWidths[2]">
                                        <payment-required-tooltip
                                            :full-access="!!$page.project.full_access"
                                            v-slot="{ on }"
                                        >
                                            <span v-on="on">
                                                <v-btn
                                                    class="text-capitalize font-weight-normal p-0 ml-8"
                                                    :class="!$page.project.full_access ? 'disabled-events' : ''"
                                                    text
                                                    tile
                                                    :color="item.is_deleted? 'grey' : 'blue darken-5'"
                                                    @click="openMessagingDialog(item.documentation_id)"
                                                >
                                                    {{ formatName(item.name, 15) }}
                                                </v-btn>
                                            </span>
                                        </payment-required-tooltip>
                                    </td>
                                    <td :style="subTableWidths[3]">
                                        <status-light
                                            v-if="!item.is_deleted"
                                            class="mr-2"
                                            :color="item.status.color"
                                        />

                                        <span v-if="item.approved_by_upload">Uploaded</span>
                                        <span v-else>{{ item.messaging_status }}</span>

                                        <span v-if="item.messaging_status === 'Late'">(-{{ item.days_late }})</span>
                                    </td>
                                    <td :style="subTableWidths[4]">
                                        <span v-if="! isDocumentApprovedOrDeleted(item)">
                                            {{ item.ball_in_court }}
                                        </span>
                                    </td>
                                    <td :style="subTableWidths[5]">
                                        <template v-if="! isDocumentApprovedOrDeleted(item)">
                                            <v-checkbox
                                                v-if="!item.is_deleted && isUserDisciplineLead"
                                                color="blue darken-5"
                                                v-model="selectedDocumentsForAssignmentEdit"
                                                :value="item.documentation_id"
                                                hide-details
                                            >
                                                <template v-slot:label>
                                                    <span class="label-text"> {{ item.assigned }}</span>
                                                </template>
                                            </v-checkbox>
                                            <span v-else class="label-text"> {{ item.assigned }}</span>
                                        </template>
                                    </td>
                                    <td :style="subTableWidths[6]">
                                        <span v-if="! isDocumentApprovedOrDeleted(item)">{{ item.assigned_date }}</span>
                                    </td>
                                    <td :style="subTableWidths[7]">
                                        <template v-if="! isDocumentApprovedOrDeleted(item)">
                                            <v-checkbox
                                                v-if="!item.is_deleted && item.messaging_status !== 'Approved' && isUserDisciplineLead"
                                                color="blue darken-5"
                                                v-model="selectedDocumentsForDueDateEdit"
                                                :value="item.documentation_id"
                                                hide-details
                                            >
                                                <template v-slot:label>
                                                <span class="label-text">{{
                                                        item.due_date === null && item.messaging_status !== 'Approved' ? 'TBD by Assignee' : item.due_date
                                                    }}</span>
                                                </template>
                                            </v-checkbox>
                                            <span v-else class="label-text">{{
                                                    item.due_date === null && item.messaging_status !== 'Approved' ? 'TBD by Assignee' : item.due_date
                                                }}</span>
                                        </template>

                                    </td>
                                    <td class="text-center" :style="subTableWidths[8]">
                                        <template v-if="isDocumentAutoApprovable(item)">
                                            <v-btn @click="openAutoApprovalDialog(item.documentation_id)" icon>
                                                <v-icon color="blue darken-5">mdi-cloud-upload-outline</v-icon>
                                            </v-btn>
                                            <v-btn icon>
                                                <v-icon @click="openDeleteDocumentPrompt(item)" color="red">mdi-delete
                                                </v-icon>
                                            </v-btn>
                                        </template>

                                        <span v-else>{{ item.approved_date }}</span>

                                    </td>
                                </tr>
                            </td>
                        </template>

                        <template v-slot:item.name="{ item }">
                            <payment-required-tooltip
                                :full-access="!!$page.project.full_access"
                                v-slot="{ on }"
                            >
                                <span v-on="on">
                                    <v-btn
                                        class="text-capitalize font-weight-normal p-0"
                                        :class="!$page.project.full_access ? 'disabled-events' : ''"
                                        text
                                        tile
                                        :color="item.is_deleted? 'grey' : 'blue darken-5'"
                                        @click="openMessagingDialog(item.documentation_id)"
                                    >{{ formatName(item.name) }}
                                    </v-btn>
                                </span>
                            </payment-required-tooltip>
                        </template>

                        <template v-slot:item.messaging_status="{ item }">
                            <status-light
                                v-if="!item.is_deleted"
                                class="mr-2"
                                :color="item.status.color"
                            />
                            <span v-if="item.approved_by_upload">Uploaded</span>
                            <span v-else>{{ item.messaging_status }}</span>

                            <span v-if="item.messaging_status === 'Late'">(-{{ item.days_late }})</span>
                        </template>

                        <template v-slot:item.ball_in_court="{ item }">
                            <span v-if="! isDocumentApprovedOrDeleted(item)">
                                {{ item.ball_in_court }}
                            </span>
                        </template>

                        <template v-slot:item.assigned="{ item }">
                            <template v-if="! isDocumentApprovedOrDeleted(item)">
                                <v-checkbox
                                    v-if="!item.is_deleted && isUserDisciplineLead"
                                    color="blue darken-5"
                                    v-model="selectedDocumentsForAssignmentEdit"
                                    :value="item.documentation_id"
                                    hide-details
                                >
                                    <template v-slot:label>
                                        <span class="label-text"> {{ item.assigned }}</span>
                                    </template>
                                </v-checkbox>
                                <span v-else class="label-text"> {{ item.assigned }}</span>
                            </template>
                        </template>

                        <template v-slot:item.assigned_date="{ item }">
                            <span v-if="! isDocumentApprovedOrDeleted(item)">{{ item.assigned_date }}</span>
                        </template>

                        <template v-slot:item.due_date="{ item }">
                            <template v-if="! isDocumentApprovedOrDeleted(item)">
                                <v-checkbox
                                    v-if="!item.is_deleted && item.messaging_status !== 'Approved' && isUserDisciplineLead"
                                    color="blue darken-5"
                                    v-model="selectedDocumentsForDueDateEdit"
                                    :value="item.documentation_id"
                                    hide-details
                                >
                                    <template v-slot:label>
                                    <span class="label-text">{{
                                            item.due_date === null && item.messaging_status !== 'Approved' ? 'TBD by Assignee' : item.due_date
                                        }}</span>
                                    </template>
                                </v-checkbox>
                                <span v-else class="label-text">{{
                                        item.due_date === null && item.messaging_status !== 'Approved' ? 'TBD by Assignee' : item.due_date
                                    }}</span>
                            </template>
                        </template>

                        <template v-slot:item.approved_date="{ item }">

                            <template v-if="isDocumentAutoApprovable(item)">
                                <v-btn @click="openAutoApprovalDialog(item.documentation_id)" icon>
                                    <v-icon color="blue darken-5">mdi-cloud-upload-outline</v-icon>
                                </v-btn>

                                <v-btn
                                    v-if="isUserDisciplineLead && documentType === 'Submittal'"
                                    @click="openAdditionalAssociatedDocumentsDialog(item)"
                                    icon
                                >
                                    <v-icon
                                        small
                                        color="blue darken-5"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </v-btn>

                                <v-btn icon>
                                    <v-icon @click="openDeleteDocumentPrompt(item)" color="red">mdi-delete</v-icon>
                                </v-btn>
                            </template>

                            <span v-else>{{ item.approved_date }}</span>

                        </template>
                    </v-data-table>
                </div>

            </v-card>
        </v-col>

        <!--       Messaging Dialog        -->
        <v-dialog v-model="openMessaging" persistent>
            <messaging-container
                v-if="selectedDocument"
                :document="selectedDocument"
                @close="closeMessagingDialog"
            ></messaging-container>
        </v-dialog>
        <!--     End Messaging Dialog     -->

        <!--  Edit Documentation Dialog      -->
        <v-dialog v-model="editDialog" persistent max-width="1000px">
            <edit-documentation
                v-if="editDialog"
                :documentation-ids="selectedDocumentsForAssignmentEdit"
                :team="selectedProjectDiscipline.team.concat(selectedProjectDiscipline.lead)"
                @submitted="closeEditAssignmentDialogAndResetSelections"
                @close="closeEditDialog"
            />
        </v-dialog>
        <!--  End Edit Documentation Dialog      -->

        <!--  Edit Due Date Dialog       -->
        <v-dialog v-model="editDueDateDialog" persistent max-width="600px">
            <edit-documentation-due-date
                v-if="editDueDateDialog"
                :documentation-ids="selectedDocumentsForDueDateEdit"
                @submitted="closeEditDueDateDialogAndResetSelections"
                @close="closeEditDueDateDialog"
            />
        </v-dialog>
        <!--  End Edit Due Date Dialog      -->


        <!--  Auto Approve Modal      -->
        <v-dialog v-model="autoApproveDialog" persistent max-width="800px">
            <manual-approval
                v-if="selectedDocumentForApproval"
                :document="selectedDocumentForApproval"
                @close="closeAutoApproveDialog"
            />
        </v-dialog>
        <!--  End Auto Approve Modal      -->


        <!--  Add additional Associated Documents Modal      -->
            <v-dialog v-model="additionalAssociatedDocumentsDialog" persistent max-width="1000px">
                <add-associated-documents-modal
                    v-if="selectedDocumentForAdditionalDocuments"
                    :document="selectedDocumentForAdditionalDocuments"
                    @submitted="closeAdditionalDocumentsDialog"
                    @close="closeAdditionalDocumentsDialog"
                />
            </v-dialog>

        <!--  End  Add additional Associated Documents Modal        -->


        <!--    Confirm Document Deletion Modal      -->

        <confirmation-modal
            v-if="selectedDocumentForDelete"
            :dialog="deleteDialog"
            @close="cancelDeleteDocument"
            @confirm="deleteDocument"
        >
            <template v-slot:title>
                Delete Document
            </template>

            <template v-slot:default>
                Are you sure you want to delete this document{{ !!selectedDocumentForDelete.associated_documents && selectedDocumentForDelete.associated_documents.length > 0 ? " and all of its' associated documents?" : '?'}}
            </template>

            <template v-slot:action-text>
                Delete
            </template>
        </confirmation-modal>

        <!--  End Confirm Document Deletion Modal      -->
    </v-row>
</template>

<script>
import Layout from "../../../../Shared/Layout";
import ProjectShowHeader from "../../../../Shared/ProjectShowHeader";
import MessagingContainer from "../../../../Shared/Documentation/MessagingContainer";
import {store} from "../../../../project-store";
import DisciplineSelector from "../../../../Shared/DisciplineSelector";
import InitializesDisciplineSelection from "../../../../mixins/InitializesDisciplineSelection";
import EditDocumentation from "../../../../Shared/Documentation/EditDocumentation";
import StatusLight from "../../../../Shared/StatusLight";
import EditDocumentationDueDate from "../../../../Shared/Documentation/EditDocumentationDueDate";
import PaymentRequiredTooltip from "../../../../Shared/PaymentRequiredTooltip";
import ManualApproval from "../../../../Shared/Documentation/ManualApproval";
import ConfirmationModal from "../../../../Shared/Documentation/ConfirmationModal";
import AddAssociatedDocumentsModal from "./AddAssociatedDocumentsModal";

export default {
    components: {
        ManualApproval,
        PaymentRequiredTooltip,
        EditDocumentationDueDate,
        StatusLight,
        EditDocumentation,
        MessagingContainer,
        DisciplineSelector,
        ConfirmationModal,
        AddAssociatedDocumentsModal
    },

    mixins: [
        InitializesDisciplineSelection
    ],

    layout: (h, component) => {
        const {organization, project} = component.data.props;
        return h(Layout, [
            h(ProjectShowHeader, {
                props: {
                    organization,
                    project,
                    tab: 'documentation'
                }
            }, [component]),
        ])
    },

    props: {
        documentType: {
            type: String
        },
        documents: {
            type: Array
        },
        disciplines: {
            type: Array
        }
    },

    data: vm => {
        const headerWidths = [
            '150',
            '420',
            '150',
            '200',
            '175',
            '130',
            '130',
            '125'
        ];

        return {
            table: {
                headers: [
                    {
                        text: 'Spec Section No.',
                        width: headerWidths[0],
                        value: 'csi_id',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Spec Title',
                        width: headerWidths[1],
                        value: 'name',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Status',
                        width: headerWidths[2],
                        value: 'messaging_status',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Ball In Court',
                        width: headerWidths[3],
                        value: 'ball_in_court',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Assigned to',
                        width: headerWidths[4],
                        value: 'assigned',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Date Assigned',
                        width: headerWidths[5],
                        value: 'assigned_date',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Due Date',
                        width: headerWidths[6],
                        value: 'due_date',
                        class: 'subtitle-2 font-weight-bold'
                    },
                    {
                        text: 'Date Approved',
                        width: headerWidths[7],
                        value: 'approved_date',
                        align: 'center',
                        class: 'subtitle-2 font-weight-bold'
                    }
                ]
            },
            search: '',
            openMessaging: false,
            selectedDocument: null,
            editDialog: false,
            deleteDialog: false,
            editDueDateDialog: false,
            selectedDocumentForDelete: null,
            autoApproveDialog: false,
            selectedDocumentForApproval: null,
            additionalAssociatedDocumentsDialog: false,
            selectedDocumentForAdditionalDocuments: null,
            subTableWidths: [
                vm.generateTableCellStyle('56'),
                vm.generateTableCellStyle(headerWidths[0]),
                vm.generateTableCellStyle(headerWidths[1]),
                vm.generateTableCellStyle(headerWidths[2]),
                vm.generateTableCellStyle(headerWidths[3]),
                vm.generateTableCellStyle(headerWidths[4]),
                vm.generateTableCellStyle(headerWidths[5]),
                vm.generateTableCellStyle(headerWidths[6]),
                vm.generateTableCellStyle(headerWidths[7]),
            ],
            selectedDocumentsForAssignmentEdit: [],
            selectedDocumentsForDueDateEdit: []
        }
    },

    computed: {
        selectedProjectDisciplineId() {
            return store.selectedProjectDisciplineId;
        },
        documentsForSelectedDiscipline() {
            return this.documents.filter(doc => doc.project_discipline_id === this.selectedProjectDisciplineId);
        },
        documentIdsForSelectedDiscipline() {
            return this.documentsForSelectedDiscipline
                .filter(document => !document.is_deleted && document.messaging_status !== 'Approved')
                .map(({id}) => id);
        },
        allSelectedForAssignEdit() {
            return this.documentIdsForSelectedDiscipline.length > 0 && this.documentIdsForSelectedDiscipline.length === this.selectedDocumentsForAssignmentEdit.length;
        },
        allSelectedForDueDateEdit() {
            return this.documentIdsForSelectedDiscipline.length > 0 && this.documentIdsForSelectedDiscipline.length === this.selectedDocumentsForDueDateEdit.length;
        },
        selectedProjectDiscipline() {
            return this.disciplines.find(projectDiscipline => projectDiscipline.id === this.selectedProjectDisciplineId);
        },
        isUserDisciplineLead() {
            return this.$page.auth.user.id === this.selectedProjectDiscipline.lead.id;
        },
        onlyOneDiscipline() {
            return this.disciplines.length === 1;
        },
        documentTypeTitle() {
            switch (this.documentType) {
                case 'General Requirement':
                    return 'General Requirements';
                case 'Submittal':
                    return 'Submittals';
                case 'TAB/CX/LEED':
                    return 'TAB/Cx/LEED';
                default:
                    return this.documentType;
            }
        }
    },

    methods: {
        formatName(name, offset = 0) {
            return name.length >= (45 - offset) ? `${name.substr(0, 45 - offset)}...` : name;
        },
        async openMessagingDialog(documentationId) {
            this.selectedDocument = await this.getDocumentationData(documentationId);
            this.openMessaging = true;
        },
        closeMessagingDialog() {
            this.selectedDocument = null;
            this.openMessaging = false;
        },
        async getDocumentationData(documentationId) {
            const {data} = await window.axios.get(this.route('documentation.ajax', [documentationId]));
            return data;
        },
        closeEditDialog() {
            this.editDialog = false;
        },
        closeEditDueDateDialog() {
            this.editDueDateDialog = false;
        },
        closeEditDueDateDialogAndResetSelections() {
            this.closeEditDueDateDialog();
            this.selectedDocumentsForDueDateEdit = [];
        },
        closeEditAssignmentDialogAndResetSelections() {
            this.closeEditDialog();
            this.selectedDocumentsForAssignmentEdit = [];
        },
        async openAutoApprovalDialog(documentationId) {
            this.selectedDocumentForApproval = await this.getDocumentationData(documentationId);
            this.autoApproveDialog = true;
        },
        closeAutoApproveDialog() {
            this.selectedDocumentForApproval = null;
            this.autoApproveDialog = false;
        },
        generateTableCellStyle(width) {
            return `width: ${width}px; min-width: ${width}px;`;
        },
        subTableWidthCalc() {
            return [
                this.generateTableCellStyle(this.$refs.table.widths[0]),
                this.generateTableCellStyle(this.$refs.table.widths[1]),
                this.generateTableCellStyle(this.$refs.table.widths[2]),
                this.generateTableCellStyle(this.$refs.table.widths[3]),
                this.generateTableCellStyle(this.$refs.table.widths[4]),
                this.generateTableCellStyle(this.$refs.table.widths[5]),
                this.generateTableCellStyle(this.$refs.table.widths[6]),
                this.generateTableCellStyle(this.$refs.table.widths[7]),
                this.generateTableCellStyle(this.$refs.table.widths[8]),
            ];
        },
        handleExpand() {
            // when the expansion panel is opened in large screens, the widths do not recalculate and propagate to the children rows
            // we want to fire the recalculation to notify our watcher to re-fire the sub row width calculations
            // without the timeout it looks like the watcher fires prior to completion of the width recalculation
            // not ideal but seems to consistently line the columns up for now
            setTimeout(() => this.$refs.table.calcWidths(), 1);
        },
        submittalHasAssociatedDocumentNeedingAction(submittal) {
            if (!submittal.associated_documents) {
                return false;
            }

            return submittal.associated_documents.some((doc) => doc.status.status === 'Warning' || doc.status.status === 'Issue');
        },
        isDocumentAutoApprovable(document) {
            if (document.messaging_status === 'Approved') {
                return false;
            }

            if (document.is_deleted) {
                return false;
            }

            if (!this.isUserDisciplineLead && !this.$page.auth.user.can.create.approvals) {
                return false;
            }

            return true;
        },
        editMultipleAssignments() {
            this.editDialog = true;
        },
        editMultipleDueDates() {
            this.editDueDateDialog = true;
        },
        toggleAllAssigned() {
            if (this.selectedDocumentsForAssignmentEdit.length > 0 && this.allSelectedForAssignEdit) {
                this.selectedDocumentsForAssignmentEdit = [];
                return;
            }

            this.selectedDocumentsForAssignmentEdit = this.documentIdsForSelectedDiscipline;
        },
        toggleAllDueDates() {
            if (this.selectedDocumentsForDueDateEdit.length > 0 && this.allSelectedForDueDateEdit) {
                this.selectedDocumentsForDueDateEdit = [];
                return;
            }

            this.selectedDocumentsForDueDateEdit = this.documentIdsForSelectedDiscipline;
        },
        clearSelectedDocuments() {
            this.selectedDocumentsForDueDateEdit = [];
            this.selectedDocumentsForAssignmentEdit = [];
        },
        isDocumentApprovedOrDeleted(document) {
            return document.messaging_status === 'Approved' || document.is_deleted;
        },
        openDeleteDocumentPrompt(document) {
            this.deleteDialog = true;
            this.selectedDocumentForDelete = document;
        },
        deleteDocument() {
            this.$inertia.delete(this.route('documentation.ajax.delete', [this.selectedDocumentForDelete.id]), {
                preserveScroll: true,
                preserveState: true
            });

            this.selectedDocumentForDelete = null;
            this.deleteDialog = false;
        },
        cancelDeleteDocument() {
            this.deleteDialog = false;
            this.selectedDocumentForDelete = null;
        },
        async openAdditionalAssociatedDocumentsDialog(document) {
            this.additionalAssociatedDocumentsDialog = true;
            this.selectedDocumentForAdditionalDocuments = document;
        },
        closeAdditionalDocumentsDialog() {
            this.additionalAssociatedDocumentsDialog = false;
            this.selectedDocumentForAdditionalDocuments = null;
        }
    },

    created() {
        this.initializeSelectDiscipline(this.disciplines.map(({id}) => id));
    },

    mounted() {
        this.subTableWidths = this.subTableWidthCalc();

        this.$watch(
            () => {
                return this.$refs.table.widths;
            },
            (items) => {
                this.subTableWidths = this.subTableWidthCalc();
            }
        );
    }

}
</script>

<style scoped>
.disabled-events {
    pointer-events: none;
}

.v-input--selection-controls {
    margin-top: 0;
}

.v-input {
    font-size: inherit;
}

.label-text {
    font-size: 0.875rem;
    color: black;
}
</style>
