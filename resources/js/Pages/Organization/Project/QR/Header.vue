<template>
    <div  class="blue darken-5">
        <div>
            <v-row justify="center">
                <v-col class="py-0" cols="10">
                    <v-row justify="center">
                        <div class="white--text font-weight-bold project-title header-text">
                            {{ project.name }}
                        </div>
                    </v-row>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col class="py-0" cols="10">
                    <v-row justify="center">
                        <div class="white--text">
                            {{ project.address_1}}
                        </div>
                    </v-row>
                    <v-row justify="center">
                        <div class="white--text">
                            {{ project.city }}, {{ project.state }} {{ project.zip }}
                        </div>
                    </v-row>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col class="py-2" cols="10">
                    <v-row justify="center">
                        <div class="white--text font-italic font-weight-light">
                            for
                        </div>
                    </v-row>
                </v-col>
            </v-row>
            <v-row justify="center">
                <v-col class="py-0" cols="10">
                    <v-row justify="center">
                        <div class="white--text">
                            {{ project.client_name }}
                        </div>
                    </v-row>
                </v-col>
            </v-row>
        </div>
        <div>
            <v-row justify="center">
                <v-col class="py-2" cols="10">
                    <slot></slot>
                </v-col>
            </v-row>
        </div>
    </div>
</template>

<script>
export default {
    name: "Header",
        props: {
            project: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    .project-title {
        font-size: 1.25rem;
    }
</style>
