import {mutations, store} from "../project-store";

export default {
    methods: {
        initializeSelectDiscipline(projectDisciplineIds) {
            if (!store.selectedProjectDisciplineId || ! projectDisciplineIds.includes(store.selectedProjectDisciplineId)) {
                mutations.setSelectedProjectDisciplineId(projectDisciplineIds[0]);
            }
        }
    }
}
