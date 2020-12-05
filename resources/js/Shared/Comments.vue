<template>
    <div>
        <div class="font-weight-bold subtitle-1 pb-4">{{title}}:</div>
        <v-textarea
            :value="value"
            @input="$emit('input', $event)"
            :placeholder="`${title}...`"
            outlined
            no-resize
            color="blue darken-5"
            :rules="rules()"
            :label="`(${remainingChars} characters remaining)`"
        >

        </v-textarea>
    </div>
</template>

<script>
    export default {
        props: {
            value: {

            },
            required: {
                type: Boolean,
                default: false
            },
            title: {
                type: String,
                default: 'Comments'
            }
        },

        methods: {
            requireMaxLength() {
                return [
                    val => (val || '').length <= 500 || `${this.title} must be 500 characters or less.`,
                ]
            },
            require() {
                return [
                    val => !!val || `${this.title} required.`,
                ]
            },
            rules() {
                if (this.required) {
                   return this.require().concat(this.requireMaxLength());
                }

                return this.requireMaxLength();
            }
        },

        computed: {
            remainingChars() {
                const length = 500 - this.value.length;
                return length >= 0 ? length : 0;
            },
        }
    }
</script>
