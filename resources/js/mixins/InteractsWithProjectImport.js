export default {

    data() {
        return {
            selectedImporetedProject: null,
            selectedProjectImport: false
        }
    },

    methods: {
        setSelectedProject(project) {
            this.selectedImporetedProject = project;
        },
        setSelectedProjectAllow(status) {
            this.selectedProjectImport = status;
        }
    },

    computed: {
    },

    created() {
        this.selectedGuest = this.projectDisciplineWithTeam.lead;
    }
}
