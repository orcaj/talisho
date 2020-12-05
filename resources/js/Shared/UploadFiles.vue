<template>
    <div>
        <div class="font-weight-bold subtitle-1 pb-3">Attach File(s):</div>

        <v-file-input
            :value="value"
            class="pt-0"
            @change="handleChange"
            :clearable="false"
            color="blue darken-5"
            multiple
            placeholder="Choose File(s)"
            :rules="mergedRules"
            :accept="acceptableFileTypesPrecedingPeriod"
        >
            <template v-slot:selection="{ index, text }">
                <v-chip
                    close
                    class="font-weight-bold"
                    @click:close="removeFile(index)"
                    text-color="blue darken-5"
                >
                    {{ text }}
                </v-chip>
            </template>
        </v-file-input>
    </div>
</template>

<script>
    export default {
        props: {
            value: {
                type: Array,
                required: true
            },
            rules: {
                type: Array,
                default: () => []
            }
        },

        data() {
            return {
                acceptableFileTypes: ''
            }
        },

        methods: {
            removeFile(index) {
                this.$emit('remove', index);
            },
            handleChange(newFiles) {
                if (newFiles === this.value) {
                    return;
                }

                if (this.value.length > 0) {
                    this.$emit('input', this.value.concat(newFiles));
                } else {
                    this.$emit('input', newFiles);
                }
            },
            async getAcceptableFileTypes() {
                const { data } = await window.axios.get(this.route('acceptable-file-types'));

                this.acceptableFileTypes = data;
            }
        },

        computed: {
            mergedRules() {
                return this.rules.concat([
                    (files) => !files.some(file => file.size > 104857600) || 'Files cannot exceed 100MB',
                    (files) => files.every(file => this.acceptableFileTypesArray.includes(file.name.split('.').pop())) || `Only ${this.formatAcceptableFileTypesForDisplay} file types supported.`
                ]);
            },
            acceptableFileTypesIncludingUpperCase() {
                return this.acceptableFileTypes.concat(',').concat(this.upperCaseFileExtensions);
            },
            upperCaseFileExtensions() {
                return this.acceptableFileTypes.split(',').map(ext => ext.toUpperCase()).join(',');
            },
            acceptableFileTypesPrecedingPeriod() {
                return this.acceptableFileTypesArray.map(ext => `.${ext}`).join(',');
            },
            acceptableFileTypesArray() {
                return this.acceptableFileTypesIncludingUpperCase.split(',');
            },
            formatAcceptableFileTypesForDisplay() {
                return this.acceptableFileTypes.split(',').map(ext => ` ${ext}`).join(',');
            }
        },

        created() {
            this.getAcceptableFileTypes();
        }
    }
</script>
