<template>
    <div v-if="projectDisciplines.length > 1" class="d-flex align-center">
        <v-select
            :value="selectedProjectDisciplineId"
            @change="updateDiscipline"
            :items="projectDisciplines"
            item-value="id"
            dense
            class="justify-content-end"
            color="blue darken-5"
            hide-details
            solo
        >
            <template v-slot:item="{ item }">
                <div>{{ item.discipline.label }}, {{ item.lead.name }}</div>
            </template>
            <template v-slot:selection="{ item }">
                <div class="text-truncate">{{ item.discipline.label }}, {{ item.lead.name }}</div>
            </template>
        </v-select>
    </div>
</template>

<script>
    import {mutations, store} from "../project-store";

    export default {

        props: {
            projectDisciplines: {
                type: Array,
                required: true,
                validator: (prop) => prop.length > 0
            }
        },

        computed: {
            selectedProjectDisciplineId() {
                return store.selectedProjectDisciplineId;
            },
            allProjectDisciplineIds() {
                return this.projectDisciplines.map(projectDiscipline => projectDiscipline.id);
            }
        },

        methods: {
            updateDiscipline(id) {
                mutations.setSelectedProjectDisciplineId(id);
                this.$emit('updated', id);
            },
        },

    }
</script>
