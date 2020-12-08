<template>
    <v-container class="fill-height">
        <v-row align="center" justify="center">
            <v-col cols="12" :lg="lgSize" md="8">
                <v-card class="elevation-12">
                    <img
                        :width="128"
                        :height="36"
                        :src="'/images/taliho-horiz-logo-one-color-rgb.svg'"
                        alt="Taliho Logo"
                        style="width:100%;"
                        class="mt-8"
                    />

                    <v-row justify="center">
                        <v-card-title> {{ title }}</v-card-title>
                    </v-row>
                    <v-card-text>
                        <slot></slot>
                    </v-card-text>
                    <v-card-actions>
                        <slot name="actions"></slot>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
        lgSize: {
            type: String,
            default: '4',
            required: true
        }
    }
};
</script>
