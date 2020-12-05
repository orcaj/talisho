export default {
    props: {
        projectDisciplineWithTeam: {
            type: Object
        },
    },

    data() {
        return {
            selectedGuest: null,
        }
    },

    methods: {
        setSelectedGuest(guest) {
            this.selectedGuest = guest;
        },
    },

    computed: {
        teamAndLead() {
            return this.projectDisciplineWithTeam.team.concat(this.projectDisciplineWithTeam.lead);
        }
    },

    created() {
        this.selectedGuest = this.projectDisciplineWithTeam.lead;
    }
}
