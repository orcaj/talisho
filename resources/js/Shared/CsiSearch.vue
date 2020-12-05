<template>
    <div>
        <span
                class="font-weight-medium"
                :class="disabled ? 'disabled-text-color' : ''"
        >{{ displayText.title }}</span>
        <v-combobox
            v-model="searched"
            :search-input.sync="searched"
            :rules="rules"
            outlined
            dense
            clearable
            hide-selected
            hide-no-data
            :items="csiList"
            :item-text="label"
            @change="emitSelect"
            @update:search-input="emitInput"
            return-object
            :placeholder="displayText.placeholder"
            append-outer-icon="search"
            class="py-2 my-4"
            validate-on-blur
            @click:clear="emitCleared"
            :disabled="disabled"
        >
            <template v-slot:item="{ item }">
                <v-list-item-content>
                    <v-list-item-subtitle v-text="item.division.name"
                                          :class="item.disabled ? 'grey--text' : ''"></v-list-item-subtitle>
                    <span :class="['text--lighten-2 pt-1', item.disabled ? 'grey--text' : 'blue--text']">
                    {{ label(item) }}
                </span>
                </v-list-item-content>
                <v-list-item-action>
                <span v-if="idsOfDocumentsAlreadyIncludedInProject.includes(item.id)" class="green--text">
                    <v-icon small color="green">mdi-checkbox-marked-circle</v-icon>
                    Added to project
                </span>
                    <span v-if="item.disabled" class="grey--text">
                    * See Quick Selection List above to add
                </span>
                </v-list-item-action>
            </template>

        </v-combobox>
    </div>
</template>

<script>
    export default {
        props: {
            csiList: {
                type: Array,
                default: () => []
            },
            idsOfDocumentsAlreadyIncludedInProject: {
                type: Array,
                default: () => []
            },
            multiple: {
                type: Boolean,
                default: true
            },
            displayText: {
                type: Object
            },
            rules: {
                type: Array,
                default: () => []
            },
            disabled:{
                type: Boolean,
                required: true
            }
        },

        data() {
            return {
                searched: '',
            }
        },

        methods: {
            label(csi) {
                return `${csi.name} (${csi.code})`;
            },
            emitSelect(selectedCsi) {
                if (selectedCsi) {
                    this.$emit('selected', selectedCsi);
                }

                if (this.multiple) {
                    this.searched = '';
                }
            },
            emitInput: _.debounce(function (value) {
                this.$emit('input', value);
            }, 400),
            emitCleared() {
                if (! this.multiple) {
                    this.$emit('clear');
                }
            },
            clearSelected() {
                this.searched = '';
            }
        }

    }
</script>
