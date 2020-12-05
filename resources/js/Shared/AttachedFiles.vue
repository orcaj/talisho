<template>
    <div>
        <span v-if="files.length === 0">N/A</span>
        <v-chip v-for="file in files"
                :key="file.id"
                text-color="blue darken-5"
                class="font-weight-bold mx-1"
                :href="route('download-file', [file.id])"
        >
            {{ file.name }}
        </v-chip>
    </div>
</template>

<script>
    export default {
        props: {
            files: {
                type: Array,
                default: () => []
            }
        }
    }
</script>
