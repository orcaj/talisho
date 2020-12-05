<template>
    <v-dialog v-model="dialog" @input="v => v || markAsRead()" max-width="600px">
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                color="primary darken-2"
                text
                v-bind="attrs"
                v-on="on"
            >
                <v-badge
                    :content="unreadCount"
                    :value="unreadCount"
                    color="orange"
                    left
                    overlap
                >
                    <v-icon color="blue darken-5">mdi-email</v-icon>
                </v-badge>
            </v-btn>
        </template>
        <v-card>
            <v-toolbar dark color="blue darken-5">
                <v-toolbar-title class="px-4">Notifications</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon class="white--text mr-2 pr-1" @click="closeAndMarkAsRead">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>

            <v-list v-if="notifications.length > 0" class="py-0">
                <v-list-item
                    v-for="notification in notifications"
                    :key="notification.id"
                    class="px-8 py-2"
                    :class="notification.read_at !== null ? 'is-read' : 'is-unread'"
                >
                    <v-list-item-content>
                        <div class="pb-2 item-subtitle">{{ notification.created_at }}</div>
                        <div class="item-title">{{ notification.data.message }}</div>
                    </v-list-item-content>

                    <v-list-item-action>
                        <v-btn icon class="black--text" @click="softDelete(notification)">
                            <v-icon>mdi-close</v-icon>
                        </v-btn>
                    </v-list-item-action>
                </v-list-item>
            </v-list>

            <v-card-text class="py-5" v-else>
                You have no notifications for this project.
            </v-card-text>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        data() {
            return {
                dialog: false,
                notifications: []
            }
        },
        props: {
            baseNotifications: {
                type: Array,
                required: true
            },
            projectId: {
                type: Number,
                required: true
            },
        },
        mounted() {
            this.notifications = this.baseNotifications;
        },
        methods: {
            async markAsRead() {
                // Skip the axios call if there are no unread notifications
                if (this.unreadCount < 1)
                    return;

                const {data} = await window.axios.post(this.route('read-notifications', [this.$page.auth.user.id, this.projectId]));

                this.notifications = data;

                return true;
            },
            async softDelete(notification) {
                const {data} = await window.axios.post(this.route('delete-notification', [this.$page.auth.user.id, notification.id]));

                if (data.success) {
                    this.notifications = this.notifications.filter((item) => {
                        return item.id !== notification.id;
                    });
                    return true;
                } else {
                    return false;
                }
            },
            closeAndMarkAsRead() {
                this.markAsRead();

                this.dialog = false;
            }
        },
        computed: {
            unreadCount() {
                return this.notifications.filter((notification) => {
                    return notification.read_at === null;
                }).length;
            },
        },
    }
</script>

<style scoped>
    .v-toolbar__title {
        font-family: 'Roboto Slab', serif;
    }

    .v-list-item {
        border-bottom: 1px solid #C6C6C6
    }
    .v-list-item:last-child {
        border-bottom: none;
    }

    .v-list-item .item-subtitle {
            font-size: 0.9em;
    }

    .v-list-item.is-read {
        background-color: #eaeff6;
    }

    .v-list-item.is-unread .item-title {
        font-weight: bold;
    }
</style>
