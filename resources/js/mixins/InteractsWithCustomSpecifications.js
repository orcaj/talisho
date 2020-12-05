export default {
    data() {
        return {
            customSpecCode: '',
            customSpecName: '',
        }
    },

    methods: {
        updateCode(value) {
            this.customSpecCode = value;
        },
        updateName(value) {
            this.customSpecName = value;
        },
    }
}
