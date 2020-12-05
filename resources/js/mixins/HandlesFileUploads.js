export default {
    data() {
        return {
            files: []
        }
    },

    methods: {
        removeFile(index) {
            this.files.splice(index, 1);
        },
        appendFilesToFormData(formData) {
            this.files.forEach((file, index) => {
                formData.append('files[' + index + ']', file, file.name);
            });
        }
    }
}
