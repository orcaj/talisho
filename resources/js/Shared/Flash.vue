<template>
    <v-snackbar
        v-model="showFlash"
        :color="color"
        :timeout="2000"
    >
        {{ message }}
    </v-snackbar>

</template>

<script>
    export default {
        name: "Flash",
        data() {
            return {
                showFlash: false,
                message: null
            }
        },
        computed: {
            color() {
                return this.$page.flash.success ? 'green' : 'red';
            }
        },
        watch: {
            '$page.flash': {
                handler() {
                    if (this.$page.flash.success) {
                        this.showFlash = true;
                        this.message = this.$page.flash.success;
                    }

                    if (this.$page.flash.error) {
                        this.showFlash = true;
                        this.message = this.$page.flash.error;
                    }
                }
            }
        }
    }
</script>
