import Vue from 'vue';

export const store = Vue.observable({
   selectedProjectDisciplineId: null
});

export const mutations = {
    setSelectedProjectDisciplineId(projectDisciplineId) {
        store.selectedProjectDisciplineId = projectDisciplineId;
    }
};
