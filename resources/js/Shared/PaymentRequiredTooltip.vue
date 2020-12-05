<template>
    <v-tooltip :disabled="fullAccess" bottom>
        <template v-slot:activator="{ on }">
            <slot v-bind:on="on">

            </slot>
        </template>
        <span>Payment required to access this section.</span>
    </v-tooltip>
</template>

<script>
export default {
    props: {
        fullAccess: {
            type: Boolean,
            required: true
        }
    }
}
</script>
