import InteractsWithDates from "./InteractsWithDates";

export default {
    mixins: [
        InteractsWithDates
    ],

    data: vm => {
        return {
            date: null,
            dateFormatted: null,
            menu: false,
        };
    },

    computed: {
        minDate() {
            return new Date(this.twoWeeksFromToday).toISOString();
        },
        twoWeeksFromToday() {
            const now = new Date();
            return now.setDate(now.getDate() + 14);
        },
    },

    watch: {
        date(val) {
            this.dateFormatted = this.formatDate(this.date)
        },
    },

}
