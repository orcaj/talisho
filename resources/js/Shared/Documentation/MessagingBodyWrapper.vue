<template>
    <v-expansion-panel-content class="content-outline">
        <slot></slot>
    </v-expansion-panel-content>
</template>

<script>
    export default {}
</script>

<style scoped>
    .content-outline {
        border: thin solid hsl(0, 0%, 45.1%);
        border-top: 0;
    }
</style>
