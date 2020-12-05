<template>
    <v-card ref="container">
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">Add New Required Document or Submittal</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="dialog = true">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div class="py-4 px-3 px-md-12">

            <v-tabs v-model="tab" color="blue darken-5">
                <v-tab>
                    General Requirements
                </v-tab>
                <v-tab>
                    Submittals
                </v-tab>
                <v-tab class="override-forced-capitals">
                    TAB / Cx / LEED
                </v-tab>
            </v-tabs>

            <v-tabs-items v-model="tab">
                <v-tab-item :value="0">
                    <other-documentation
                        :project-discipline-with-team="projectDisciplineWithTeam"
                        :quick-list="quickLists['General Requirements']"
                        :projectsList="projectsList"
                        :ids-of-csis-in-project="idsOfCsisInProject"
                        :search-route="route('csi-search.general-requirements')"
                        :submit-route-name="'documentation.general.store'"
                        :submit-import-route-name="'documentation.general.import'"
                        :custom-specification-route="route('custom-specifications.general-requirements', [$page.organization.id])"
                        placeholder-text="Search Spec Sections 00 and 01"
                        search-title="Add Other General Requirements"
                        @scroll-to-top="scrollToTop"
                    >

                    </other-documentation>
                </v-tab-item>
                <v-tab-item :value="1">
                    <submittals
                        :project-discipline-with-team="projectDisciplineWithTeam"
                        :quick-list="quickLists['Associated Documents']"
                        :projectsList="projectsList"
                        :ids-of-csis-in-project="idsOfCsisInProject"
                        :search-route="route('csi-search.submittals')"
                        :divisions="submittalDivisions"
                        @scroll-to-top="scrollToTop"
                    >

                    </submittals>
                </v-tab-item>
                <v-tab-item :value="2">
                    <other-documentation
                        :project-discipline-with-team="projectDisciplineWithTeam"
                        :quick-list="quickLists['Tab']"
                        :projectsList="projectsList"
                        :ids-of-csis-in-project="idsOfCsisInProject"
                        :search-route="route('csi-search.tab')"
                        :submit-route-name="'documentation.tab.store'"
                        :submit-import-route-name="'documentation.tab.import'"
                        :custom-specification-route="route('custom-specifications.tab-cx-leed', [$page.organization.id])"
                        placeholder-text="Search..."
                        search-title="Add Other TAB / Cx / LEED Documents"
                        @scroll-to-top="scrollToTop"
                    >
                    </other-documentation>

                </v-tab-item>
            </v-tabs-items>
        </div>

        <confirmation-modal
            :dialog="dialog"
            @close="dialog = false"
            @confirm="$emit('close')"
        />

    </v-card>
</template>

<script>
    import OtherDocumentation from "./OtherDocumentation";
    import Submittals from "./Submittals";
    import ConfirmationModal from "../../../../Shared/Documentation/ConfirmationModal";

    export default {
        components: {
            OtherDocumentation,
            Submittals,
            ConfirmationModal
        },

        props: {
            projectDisciplineWithTeam: {
                type: Object
            },
            quickLists: {
                type: Object
            },

            projectsList: {
                type: Array
            },
            idsOfCsisInProject: {
                type: Array,
            },
            submittalDivisions: {
                type: Array
            }
        },

        methods: {
            scrollToTop() {
                this.$refs.container.$el.scrollIntoView({behavior: 'smooth'});
            }
        },

        data() {
            return {
                tab: null,
                dialog: false
            }
        },

    }
</script>

<style scoped>
    .override-forced-capitals {
        text-transform: none !important;
    }
</style>
