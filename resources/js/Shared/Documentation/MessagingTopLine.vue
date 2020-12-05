<template>
    <div>
        <v-row>
            <v-col cols="5" sm="3" md="2">
                <span class="font-weight-bold subtitle-1">To:</span>
            </v-col>
            <v-col cols="7" sm="9" md="4">
                <div class="py-2"> {{ user.name }}</div>
                <div> {{ user.organization.name }}</div>
            </v-col>
        </v-row>
        <v-row>
            <v-col cols="5" sm="3" md="2">
                <span class="font-weight-bold subtitle-1">Subject:</span>
            </v-col>
            <v-col cols="7" sm="9" md="4">
                {{ formatSubject(document) }}
            </v-col>
        </v-row>
    </div>
</template>

<script>
    export default {

        props: {
            user: {
                type: Object
            },
            document: {
                type: Object
            }
        },

        methods: {
            formatSubject(document) {
                const type = document.entity_type === 'App\\Submittal'
                    ? 'Submittal'
                    : (document.entity.document_type === 'Submittal' ?  'Associated Document' : document.entity.document_type);

                return `${type} - ${document.entity.specification.name} (${document.entity.specification.code})`;
            },
        }

    }
</script>
