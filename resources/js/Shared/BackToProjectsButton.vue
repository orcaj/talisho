<template>
    <v-btn @click="clickHandler" color="secondary" class="px-0" text>
        <v-icon
            class="pr-2"
            :color="color ? color : 'orange actions'"
        >
            mdi-arrow-left
        </v-icon>
        <span
            class="action-text subtitle-1 font-weight-bold pad-text"
            :class="color === 'black' ? 'black--text' : ''"
        >
            BACK TO PROJECTS
        </span>
    </v-btn>
</template>

<script>
    export default {
        name: "BackToProjectsButton",

        props: {
            clickHandler: {
                type: Function,
                required: true
            },
            color: {
                type: String
            }
        }
    }
</script>
