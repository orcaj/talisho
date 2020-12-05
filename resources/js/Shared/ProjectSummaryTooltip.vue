<template>
    <div>
        <div :class="summaryData['documentation_status'] === 0 ? 'invisible-text' : ''">
            <div style="text-decoration: underline; font-weight: bold">Documentation</div>
            <div class="ml-4">{{ labelForApprovedDocumentPercentage(summaryData['approved_documents'],
                summaryData['total_documents']) }}
            </div>
            <div class="ml-4">
                <span :class="summaryData['documentation_needs_action'] ? 'highlight-line' : ''">
                    {{ summaryData['documents_under_review']}} Under Review
                </span>
            </div>
            <div
                    class="ml-4"
            >
                <span
                        :class="summaryData['documents_late'] !== 0 ? 'status-warning highlight-line' : ''"
                >
                    {{ summaryData['documents_late']}} Late
                </span>

            </div>
        </div>

        <div :class="summaryData['communication_status'] === 0 ? 'invisible-text' : ''">
            <div style="text-decoration: underline; font-weight: bold">Communication</div>
            <div class="ml-4">
                <span :class="summaryData['rfi_needs_action'] ? 'highlight-line' : ''">
                    {{ summaryData['rfis_under_review']}} RFIs Under Review
                </span>
            </div>
            <div
                    class="ml-4"
            >
                <span
                        :class="summaryData['rfis_late'] !== 0 ? 'status-warning highlight-line' : ''"
                >
                    {{ summaryData['rfis_late']}} RFIs Late
                </span>

            </div>
        </div>

        <div :class="summaryData['liability_status'] === 0 ? 'invisible-text' : ''">
            <div style="text-decoration: underline; font-weight: bold">Liability Protection</div>
            <div
                    class="ml-4"
            >
                <span :class="summaryData['daily_logs_missing'] !== 0 ? 'highlight-line' : ''"
                >
                    {{ summaryData['daily_logs_missing']}} Daily Logs Missing
                </span>

            </div>
            <div class="ml-4">
                {{ summaryData['incident_reports']}} Total Incident Reports
            </div>
            <div
                    class="ml-4"
            >
                <span
                        :class="summaryData['unacknowledged_incident_reports'] !== 0 ? 'status-warning highlight-line' : ''"
                >
                    {{ summaryData['unacknowledged_incident_reports']}} Unacknowledged Incident Reports
                </span>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            summaryData: {
                type: Object,
                required: true
            }
        },

        methods: {
            labelForApprovedDocumentPercentage(approved, total) {
                if (total === 0) {
                    return 'No Documents Assigned';
                }

                return `${(approved / total * 100).toFixed(0).toString()}% Approved`;
            }
        }
    }
</script>

<style scoped>
    .status-warning {
        color: red;
        font-weight: bold;
    }

    .highlight-line {
        background-color: yellow;
    }

    .invisible-text {
        color: #cbcbcb;
    }

</style>
