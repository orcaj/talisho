export default {
    data() {
        return {
            validationErrors: {},
        }
    },
    methods: {
        getValidationError(field) {
            if(this._fieldExists(field)) {
                return this.validationErrors[field][0];
            }
            return null;
        },
        hasValidationError(field) {
          return this._fieldExists(field);
        },
        clearValidationError(field) {
            if(this.validationErrors[field]) {
                this.$delete(this.validationErrors, field);
            }
        },
        clearAllValidationErrors() {
            this.validationErrors = {};
        },
        hasValidationErrors() {
            return !_.isEmpty(this.validationErrors);
        },
        _fieldExists(field) {
            return this.validationErrors[field] && typeof this.validationErrors[field][0] !== 'undefined'
        }
    },
    watch: {
        '$page.errors': {
            handler() {
                this.validationErrors = this.$page.errors;
            },
            deep: true
        }
    }
}
