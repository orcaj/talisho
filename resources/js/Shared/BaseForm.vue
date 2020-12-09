<template>
    <v-container>
        <v-row align="center" justify="center">
            <v-col cols="12" md="8">
                <v-card class="elevation-12">
                    <div class="blue darken-5 pa-6">
                        <v-row justify="space-between">
                            <back-to-projects-button
                                v-if="!!$page.organization"
                                :click-handler="back"
                            />

                            <v-btn
                                @click="cancelSubscription"
                                color="red"
                                class="px-0"
                                text
                                dark
                                v-if="isActive === 'ACTIVE'"
                            >
                                <span
                                    class="subtitle-1 font-weight-bold pad-text "
                                >
                                    Cancel Subscription
                                </span>
                            </v-btn>
                            <v-btn
                                @click="retrySubscription"
                                color="red"
                                class="px-0"
                                text
                                dark
                                v-if="isActive === 'INACTIVE'"
                            >
                                <span
                                    class="subtitle-1 font-weight-bold pad-text "
                                >
                                    Retry Subscription
                                </span>
                            </v-btn>
                        </v-row>
                        <v-row justify="center" class="my-md-4">
                            <span class="title-text"> {{ title }}</span>
                        </v-row>
                    </div>
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
import Layout from "./Layout";
import BackToProjectsButton from "./BackToProjectsButton";

export default {
    components: { BackToProjectsButton },
    layout: Layout,
    props: {
        title: {
            type: String,
            required: true
        },
        isActive: {
            type: String,
            required: true
        }
    },
    methods: {
        back() {
            this.$inertia.visit(
                this.route("organizations.projects.index", {
                    organization: this.$page.organization.id
                })
            );
        },
        cancelSubscription() {
            this.isActive = 'INACTIVE';
            this.$inertia.post("/cancel_subscription");
        },
        retrySubscription() {
            this.isActive = 'ACTIVE';
            this.$inertia.post("/retry_subscription");
        }
    }
};
</script>

<style scoped>
.title-text {
    font-family: "Roboto Slab", serif;
    font-size: 24px;
    color: white;
}
</style>
