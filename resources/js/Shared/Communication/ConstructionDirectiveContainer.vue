<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title class="px-4">
                {{ !!directive ? `Construction Directive ${directive.identifier}` : 'New Construction Directive'}}
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-toolbar-items>
                <v-btn icon dark @click="confirmExitDialog = true">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar-items>
        </v-toolbar>

        <div v-if="!!directive" class="py-4 elevation-4">
            <span class="subtitle-1 font-weight-bold pl-10 pr-2">Status:</span>
            <v-tooltip bottom>
                <template v-slot:activator="{ on }">
                    <v-chip v-on="on" color="green lighten-3"><span class="font-weight-bold">Finalized</span></v-chip>
                </template>
                <span>Construction Directive is finalized.</span>
            </v-tooltip>
        </div>

        <div class="py-4 px-3 px-sm-6 px-md-12">
            <v-expansion-panels v-model="panels" multiple flat tile>
                <view-construction-directive v-if="directive" :directive="directive" />
                <create-construction-directive
                    v-else
                    :discipline="discipline"
                    @cancel="confirmExitDialog = true"
                    @close="$emit('close')"
                />
            </v-expansion-panels>
        </div>

        <div v-if="!!directive" class="px-12 pt-2 pb-4">
            <v-btn min-width="200px" color="blue darken-5" class="white--text" @click="$emit('close')">Close</v-btn>
        </div>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />
    </v-card>
</template>

<script>
    import ConfirmationModal from "../Documentation/ConfirmationModal";
    import CreateConstructionDirective from "./CreateConstructionDirective";
    import ViewConstructionDirective from "./ViewConstructionDirective";

    export default {
        components: {
            ConfirmationModal,
            CreateConstructionDirective,
            ViewConstructionDirective
        },

        props: {
            directive: {
                type: Object,
                default: null
            },
            discipline: {
                type: Object,
            }
        },

        data() {
            return {
                panels: [0],
                confirmExitDialog: false
            }
        }

    }
</script>
