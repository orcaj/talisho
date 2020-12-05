<template>
    <div>
        <v-row>
            <v-col cols="12" class="py-0">
                <span
                        class="font-weight-medium"
                        :class="disabled ? 'disabled-text-color' : ''"
                >Add Your Custom Specification:</span>
            </v-col>
        </v-row>
        <v-row align="center">
            <v-col cols="12" sm="5" md="4" lg="3" class="py-0">
                <v-combobox
                    v-model="code"
                    :rules="rules"
                    :search-input.sync="code"
                    @update:search-input="emitCode"
                    class="py-2 my-4"
                    outlined
                    dense
                    clearable
                    hide-selected
                    hide-no-data
                    :items="specificationsWithoutSelected"
                    item-text="code"
                    @change="emitSelect"
                    return-object
                    validate-on-blur
                    placeholder="Spec Number"
                    @click:clear="emitCleared"
                    :disabled="disabled"
                >
                    <template v-slot:item="{ item }">
                        <v-list-item-content>
                            <span>{{ item.code }} - {{ item.name }}</span>
                        </v-list-item-content>
                    </template>

                </v-combobox>
            </v-col>
            <v-col cols="12" sm="7" md="8" :lg="multiple ? '7' : '9'" class="py-0">
                <v-combobox
                    v-model="name"
                    :rules="rules"
                    :search-input.sync="name"
                    @update:search-input="emitName"
                    class="py-2 my-4"
                    outlined
                    dense
                    clearable
                    hide-selected
                    hide-no-data
                    :items="specificationsWithoutSelected"
                    item-text="name"
                    @change="emitSelect"
                    return-object
                    validate-on-blur
                    placeholder="Spec Name"
                    @click:clear="emitCleared"
                    append-outer-icon="nothing"
                    :disabled="disabled"
                >
                    <template v-slot:item="{ item }">
                        <v-list-item-content>
                            <span>{{ item.name }} ({{ item.code }})</span>
                        </v-list-item-content>
                    </template>

                </v-combobox>
            </v-col>
            <v-btn
                v-if="multiple"
                outlined
                color="blue darken-5"
                class="ml-4 mb-5 px-0"
                :disabled="disableButton || disabled"
                @click="emitCustomSpecification"
            >
                <v-icon color="blue darken-5">mdi-plus</v-icon>
            </v-btn>
        </v-row>
    </div>
</template>

<script>
export default {
    name: "CustomSpecification",
    data() {
        return {
            code: '',
            name: '',
            specifications: []
        }
    },

    props: {
        rules: {
            type: Array,
            default: () => []
        },
        multiple: {
            type: Boolean,
            default: false
        },
        customSpecRoute: {
            type: String,
            required: true
        },
        selected: {
            type: Array,
            default: () => []
        },
        disabled:{
            type: Boolean,
            required: true
        }
    },

    computed: {
        specificationsWithoutSelected() {
            return this.specifications.filter(spec => ! this.selectedIds.includes(spec.id));
        },
        selectedIds() {
            return this.selected.map(({id}) => id);
        },
        disableButton() {
            return !this.code || !this.name || this.codeOrNameAlreadyExist();
        },
    },

    methods: {
        codeOrNameAlreadyExist() {
            return this.specifications.some(({name, code}) => code === this.code || name === this.name);
        },
        emitCode(value) {
            this.$emit('update-code', value);
        },
        emitName(value) {
            this.$emit('update-name', value);
        },
        emitSelect(selectedSpec) {
            if (selectedSpec) {
                this.$emit('selected', selectedSpec);
            }

            if (this.multiple) {
                this.name = '';
                this.code = '';
            } else if (selectedSpec) {
                const {name, code} = this.specifications.find(({name, code}) => selectedSpec.name === name || selectedSpec.code === code);
                this.name = name;
                this.code = code;
            }
        },
        emitCustomSpecification() {
            this.$emit('add-custom-specification', {
                name: this.name,
                code: this.code,
            });

            this.name = '';
            this.code = '';
        },
        emitCleared() {
            if (! this.multiple) {
                this.$emit('clear');
                this.name = '';
                this.code = '';
            }
        },
        async loadSpecifications() {
            try {
                const {data} = await window.axios.get(this.customSpecRoute);

                this.specifications = data;
            } catch (error) {
                console.log(error);
            }
        },
        clearSelected() {
            this.code = '';
            this.name = '';
        }
    },

    created() {
        this.loadSpecifications();
    }
}
</script>
