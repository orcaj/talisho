<template>
    <div>
        <v-chip
            v-for="csi in selected"
            :key="csi.id"
            label
            close-icon="mdi-close"
            class="mr-4 mb-2 responsive"
            text-color="primary darken-4"
            color="gray lighten-2"
            @click:close="removeCsi(csi)"
            close
        >
            <strong>{{ csi.name }} ({{csi.code}})</strong>
        </v-chip>
        <v-chip
            v-for="spec in newSpecifications"
            :key="`${spec.code}-${spec.name}`"
            label
            close-icon="mdi-close"
            class="mr-4 mb-2 responsive"
            text-color="primary darken-4"
            color="gray lighten-2"
            @click:close="removeNewCustomSpecification(spec)"
            close
        >
            <strong>{{ spec.name }} ({{spec.code}})</strong>
        </v-chip>
        <v-chip
            v-for="spec in existingSpecifications"
            :key="`${spec.code}-${spec.name}`"
            label
            close-icon="mdi-close"
            class="mr-4 mb-2 responsive"
            text-color="primary darken-4"
            color="gray lighten-2"
            @click:close="removeExistingSpecification(spec)"
            close
        >
            <strong>{{ spec.name }} ({{spec.code}})</strong>
        </v-chip>
    </div>
</template>

<script>
    export default {
        props: {
            selected: {
                type: Array,
                default: () => []
            },
            newSpecifications: {
                type: Array,
                default: () => []
            },
            existingSpecifications: {
                type: Array,
                default: () => []
            }
        },

        methods: {
            removeCsi(csi) {
                this.$emit('remove-csi', csi);
            },
            removeNewCustomSpecification(spec) {
                this.$emit('remove-new-specification', spec)
            },
            removeExistingSpecification(spec) {
                this.$emit('remove-existing-specification', spec);
            }
        }
    }
</script>
