<template>
    <v-row justify="center">
        <v-col cols="10">
            <v-card>
                <v-card-title class="blue darken-5">
                    <v-row align="center" class="mx-2">
                        <span class="action-text pr-6">Design Documents</span>

                    </v-row>
                </v-card-title>

                <v-row class="py-2" justify="center">
                    <v-col cols="11" md="8">

                        <!--         Abstract these into one group for each drawing type            -->

                        <!--         Drawings                -->
                        <v-row class="px-3 pt-4" justify="space-between" align="end">
                            <h6 class="font-weight-bold text-uppercase">Drawings</h6>
                            <v-btn v-if="actionsVisibleForUser"
                                   @click="openDialog(types.DRAWING)" class="white--text" color="blue darken-5">
                                <v-icon>mdi-plus</v-icon>
                                Add New
                            </v-btn>
                        </v-row>

                        <hr class="line">

                        <v-row v-if="!documents[types.DRAWING]" justify="center">
                            <v-col cols="12" lg="6">
                                <v-row justify="center">
                                    No Drawings to View
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row v-else v-for="document in documents[types.DRAWING]" justify="center">
                            <v-col cols="3" lg="5" offset-lg="1">
                                <v-row>
                                    <a class="list-text font-weight-bold px-3 text-center"
                                       :href="routeForDownload(document)">
                                        {{ document.name }}
                                    </a>
                                    <v-icon
                                        v-if="actionsVisibleForUser"
                                        color="blue darken-5"
                                        small
                                        @click="handleEdit(document)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </v-row>
                            </v-col>
                            <v-col cols="4" lg="3">
                                <span class="px-3">
                                    {{ formatDate(document.created_at) }}
                                </span>
                            </v-col>
                            <v-col cols="3" lg="2">
                                <v-icon v-if="actionsVisibleForUser" @click="openDeleteDocumentModal(document)" color="red">mdi-delete</v-icon>
                            </v-col>
                        </v-row>


                        <!--     Specifications                   -->

                        <v-row class="px-3 pt-4" justify="space-between" align="end">
                            <h6 class="font-weight-bold text-uppercase">Specifications</h6>
                            <v-btn v-if="actionsVisibleForUser"
                                   @click="openDialog(types.SPECIFICATION)" class="white--text" color="blue darken-5">
                                <v-icon>mdi-plus</v-icon>
                                Add New
                            </v-btn>
                        </v-row>

                        <hr class="line">

                        <v-row v-if="!documents[types.SPECIFICATION]" justify="center">
                            <v-col cols="12" lg="6">
                                <v-row justify="center">
                                    No Specifications to View
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row v-else v-for="document in documents[types.SPECIFICATION]" justify="center">
                            <v-col cols="3" lg="5" offset-lg="1">
                                <v-row>
                                    <a class="list-text font-weight-bold px-3 text-center"
                                       :href="routeForDownload(document)">
                                        {{ document.name }}
                                    </a>
                                    <v-icon
                                        v-if="actionsVisibleForUser"
                                        color="blue darken-5"
                                        small
                                        @click="handleEdit(document)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </v-row>
                            </v-col>
                            <v-col cols="4" lg="3">
                                <span class="px-3">
                                    {{ formatDate(document.created_at) }}
                                </span>
                            </v-col>
                            <v-col cols="3" lg="2">
                                <v-icon v-if="actionsVisibleForUser" @click="openDeleteDocumentModal(document)" color="red">mdi-delete</v-icon>
                            </v-col>
                        </v-row>

                        <!--      Addenda                  -->

                        <v-row class="px-3 pt-4" justify="space-between" align="end">
                            <h6 class="font-weight-bold text-uppercase">Addenda</h6>
                            <v-btn v-if="actionsVisibleForUser"
                                   @click="openDialog(types.ADDENDUM)" class="white--text" color="blue darken-5">
                                <v-icon>mdi-plus</v-icon>
                                Add New
                            </v-btn>
                        </v-row>

                        <hr class="line">

                        <v-row v-if="!documents[types.ADDENDUM]" justify="center">
                            <v-col cols="12" lg="6">
                                <v-row justify="center">
                                    No Addenda to View
                                </v-row>
                            </v-col>
                        </v-row>

                        <v-row v-else v-for="document in documents[types.ADDENDUM]" justify="center">
                            <v-col cols="3" lg="5" offset-lg="1">
                                <v-row>
                                    <a class="list-text font-weight-bold px-3 text-center"
                                       :href="routeForDownload(document)">
                                        {{ document.name }}
                                    </a>
                                    <v-icon
                                        v-if="actionsVisibleForUser"
                                        color="blue darken-5"
                                        small
                                        @click="handleEdit(document)"
                                    >
                                        mdi-pencil
                                    </v-icon>
                                </v-row>
                            </v-col>
                            <v-col cols="4" lg="3">
                                <span class="px-3">
                                    {{ formatDate(document.created_at) }}
                                </span>
                            </v-col>
                            <v-col cols="3" lg="2">
                                <v-icon v-if="actionsVisibleForUser" @click="openDeleteDocumentModal(document)" color="red">mdi-delete</v-icon>
                            </v-col>
                        </v-row>

                    </v-col>
                </v-row>
            </v-card>
        </v-col>

        <!--      Upload Dialog     -->
        <v-dialog
            v-model="dialog"
            persistent
            width="500"
        >
            <upload-design-document
                v-if="dialog && selectedType"
                :document-type="selectedType"
                @close="closeDialog"
            />

        </v-dialog>
        <!--    End Upload Dialog    -->


        <!--  Edit Name Dialog      -->
        <v-dialog v-model="editDialog" persistent max-width="500px">
            <edit-design-document
                v-if="selectedDesignDocument"
                :design-document="selectedDesignDocument"
                @close="closeEditDialog"
            />
        </v-dialog>
        <!--   End Edit Name Dialog     -->

        <!--   Delete Design Doc Modal     -->
        <confirmation-modal
            v-if="documentToDelete"
            :dialog="deleteDialog"
            @close="closeDeleteConfirmation"
            @confirm="deleteDocument"
        >

            <template v-slot:title>
                Delete {{ documentToDelete.name }}
            </template>

            <template v-slot:default>
                Are you sure you want to delete this document?
            </template>

            <template v-slot:action-text>
                Delete
            </template>

        </confirmation-modal>
        <!--  End Delete Design Doc Modal      -->
    </v-row>
</template>

<script>
    import Layout from "../../../../Shared/Layout";
    import ProjectShowHeader from "../../../../Shared/ProjectShowHeader";
    import UploadDesignDocument from "../../../../Shared/DesignDocument/UploadDesignDocument";
    import InteractsWithDates from "../../../../mixins/InteractsWithDates";
    import EditDesignDocument from "../../../../Shared/DesignDocument/EditDesignDocument";
    import ConfirmationModal from "../../../../Shared/Documentation/ConfirmationModal";

    export default {
        components: {
            EditDesignDocument,
            UploadDesignDocument,
            ConfirmationModal
        },

        mixins: [
            InteractsWithDates
        ],

        layout: (h, component) => {
            const {organization, project} = component.data.props;
            return h(Layout, [
                h(ProjectShowHeader, {
                    props: {
                        organization,
                        project,
                        tab: 'design-documents'
                    }
                }, [component]),
            ])
        },

        props: {
            types: {
                type: Object,
                required: true
            },
            documents: {
                type: Object,
                required: true
            },
            disciplines: {
                type: Array,
                required: true
            }
        },

        data() {
            return {
                dialog: false,
                selectedType: null,
                editDialog: false,
                deleteDialog: false,
                selectedDesignDocument: null,
                documentToDelete: null
            }
        },

        computed: {
            userLeadsDisciplineInProject() {
                return this.disciplines.some((disc) => disc.user_id === this.$page.auth.user.id);
            },
            actionsVisibleForUser() {
                return this.$page.auth.user.can.create.designDocuments || this.userLeadsDisciplineInProject;
            }
        },

        methods: {
            closeDialog() {
                this.dialog = false;
                this.selectedType = null;
            },
            openDialog(type) {
                this.dialog = true;
                this.selectedType = type;
            },
            routeForDownload(doc) {
                if (doc.files.length > 1) {
                    return this.route('download-zip', [doc.id]);
                }

                return this.route('download-file', [doc.files[0].id])
            },
            handleEdit(designDoc) {
                this.selectedDesignDocument = designDoc;
                this.editDialog = true;
            },
            closeEditDialog() {
                this.editDialog = false;
                this.selectedDesignDocument = null;
            },
            openDeleteDocumentModal(document) {
                this.documentToDelete = document;
                this.deleteDialog = true;
            },
            deleteDocument() {
                this.$inertia.delete(this.route('organizations.projects.design-documents.delete', [this.$page.organization.id, this.$page.project.id, this.documentToDelete.id]));

                this.documentToDelete = null;
            },
            closeDeleteConfirmation() {
                this.documentToDelete = null;
                this.deleteDialog = false;
            }
        },
    }
</script>

<style scoped>
    .line {
        margin-top: 5px;
        background-color: #193C73;
        height: 4px;
    }

    .list-text {
        color: #193C73;
    }
</style>
