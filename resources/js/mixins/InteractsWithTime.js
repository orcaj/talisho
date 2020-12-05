export default {
    methods: {
        formatTime(time) {
            const [hour, mins] = time.split(':');
            const period = hour >= 12 ? 'PM' : 'AM';
            let displayHour = hour === '00'
                ? '12'
                : (hour > 12 ? (hour - 12).toString() : hour);

            if (displayHour.split('')[0] === '0') {
                displayHour = displayHour.split('')[1];
            }

            return `${displayHour}:${mins} ${period}`;
        }
    }
}
