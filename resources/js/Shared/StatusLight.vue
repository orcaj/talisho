<template>
    <span
        v-on="shouldShowTooltip && onHover"
        class="status-indicator"
        :style="{ backgroundColor: color }"
    ></span>
</template>

<script>
    export default {
        props: {
            color: {
                type: String,
                required: true
            },
            onHover: {
                type: Object
            },
            shouldShowTooltip: {
                type: Boolean,
                default: true
            }
        },
    }
</script>

<style scoped>
    .status-indicator {
        border: 2px solid black;
        border-radius: 50%;
        height: 24px;
        width: 24px;
        display: inline-block;
        vertical-align: middle;
    }
</style>
