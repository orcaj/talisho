<template>
    <v-app id="inspire">

        <v-app-bar
            app
            color="white"
        >
            <v-toolbar-title>
                <v-btn @click="home" class="px-8" text>
                    <img :width="128" :height="36" :src="'/images/taliho-horiz-logo-one-color-rgb.svg'" alt="Taliho Logo">
                </v-btn>
            </v-toolbar-title>

            <v-spacer />

            <v-toolbar-items>

                <v-btn class="mx-1 d-none d-lg-flex" color="blue darken-5" v-if="$page.auth.user && $page.auth.user.can.view.admin" @click="admin" text>
                    Admin
                </v-btn>

                <v-btn class="mx-1 d-none d-lg-flex" color="blue darken-5" v-if="$page.auth.user" @click="editProfile" text>
                    <v-icon color="blue darken-5" class="pr-1">mdi-account-circle</v-icon>
                    <span class="hidden-xs-only">{{ $page.auth.user.name }}</span>
                </v-btn>

                <v-btn color="blue darken-5" v-if="$page.auth.user" @click="logout" class="mr-12" text>
                    <v-icon color="blue darken-5">mdi-logout</v-icon>
                    <span>Logout</span>
                </v-btn>

                <v-btn color="blue darken-5" v-if="!$page.auth.user" @click="login" class="mr-12" text>
                    <v-icon color="blue darken-5">mdi-login</v-icon>
                    <span>Login</span>
                </v-btn>
            </v-toolbar-items>
        </v-app-bar>

        <v-content>
            <slot />
        </v-content>


        <v-footer
            color="black"
            app
        >
            <span class="white--text">&copy; 2020</span>
        </v-footer>

        <flash />
    </v-app>
</template>

<script>
    import Flash from "./Flash";

    export default {
        name: "Layout",
        components: {
            Flash
        },
        methods: {
            async logout() {
                this.$inertia.post(this.route('logout'))
            },
            editProfile() {
                this.$inertia.visit(this.route('users.edit', {user: this.$page.auth.user.id}));
            },
            login() {
                this.$inertia.visit(this.route('login'));
            },
            home() {
                this.$inertia.visit("/");
            },
            admin() {
                window.location = "/admin/dashboards/main";
            },
            users() {
                this.$inertia.visit(this.route('organizations.users.index', {organization: this.$page.auth.user.organization.id}));
            },
            invite() {
                this.$inertia.visit(this.route('users.create'));
            }
        },
    }
</script>
