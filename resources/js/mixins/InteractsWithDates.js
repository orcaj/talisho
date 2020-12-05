export default {
    methods: {
        parseDate(date) {
            if (!date) return null;

            const [month, day, year] = date.split('/');
            return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
        },
        formatDate(date) {
            if (!date) return null;

            const [year, month, day] = date.split('-');
            return `${month}/${day.substr(0, 2)}/${year}`;
        },
        isWeekend(day) {
            // day 5 is Saturday, day 6 is Sunday
            return day === 5 || day === 6;
        },
        allowedDates(date) {
            return !this.isWeekend(new Date(date).getDay());
        },
    }
}
