<template>
    <div class="pa-2 pa-md-8">
        <v-col>
            <span
                    class="font-weight-medium"
                    :class="disabled ? 'disabled-text-color' : ''"
            >{{ title }}</span>
            <v-row>
                <v-col cols="12" sm="6" class="py-0 py-sm-4">
                    <v-checkbox
                        v-for="item in splitList[0]"
                        color="blue darken-5"
                        @change="emitSelected(item)"
                        :key="item.id"
                        :input-value="selected.includes(item) || alreadySelectedCodes.includes(item.code)"
                        :disabled="alreadySelectedCodes.includes(item.code) || disabled"
                        hide-details
                        class="my-md-4 responsive"
                    >
                        <template v-slot:label>
                            <div
                                    :class="disabled ? 'disabled-text-color' : 'black--text'"
                            >{{ item.name }} ({{item.code}})</div>
                        </template>
                    </v-checkbox>
                </v-col>
                <v-col cols="12" sm="6" class="py-0 py-sm-4">
                    <v-checkbox
                        v-for="item in splitList[1]"
                        color="blue darken-5"
                        @change="emitSelected(item)"
                        :key="item.id"
                        :input-value="selected.includes(item) || alreadySelectedCodes.includes(item.code)"
                        :disabled="alreadySelectedCodes.includes(item.code) || disabled"
                        hide-details
                        class="my-sm-4 responsive"
                    >
                        <template v-slot:label>
                            <div
                                    :class="disabled ? 'disabled-text-color' : 'black--text'"
                            >{{ item.name }} ({{item.code}})</div>
                        </template>
                    </v-checkbox>
                </v-col>
            </v-row>
        </v-col>
    </div>
</template>

<script>
    export default {
        props: {
            title: {
                type: String,
                required: true
            },
            quickList: {
                type: Array,
                default: () => []
            },
            selected: {
                type: Array,
                default: () => []
            },
            alreadySelectedCodes: {
                type: Array,
                default: () => []
            },
            disabled:{
                type: Boolean,
                required: true
            }
        },
        computed: {
            splitList() {
                const midPoint = Math.ceil(this.quickList.length / 2);
                return [this.quickList.slice(0, midPoint), this.quickList.slice(midPoint)];
            }
        },

        methods: {
            emitSelected(csi) {
                const selected = this.selected.includes(csi) ? this.selected.filter(item => item.id !== csi.id) : this.selected.concat(csi);
                this.$emit('selected', selected);
            }
        }
    }
</script>

