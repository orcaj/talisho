<template>
    <v-row justify="space-between" class="pt-4">
        <v-col cols="6">
            <v-row>
                <h3 class="m-0" v-if="$page.auth.user.can.edit.organizations">{{ organization.name }}</h3>
                <h3 class="m-0" v-else>{{ company.name }}</h3>
                <inertia-link
                    v-if="$page.auth.user.can.edit.organizations"
                    :href="route('organizations.edit', {organization: organization})"
                    class="no-underline"
                >
                    <v-btn min-width="0" color="white" small rounded :ripple="false" text title="Edit Company Information">
                        <v-icon color="blue darken-5">mdi-settings</v-icon>
                    </v-btn>
                </inertia-link>
            </v-row>
            <v-row>
                <v-subheader class="font-weight-light p-0 pr-2" v-if="$page.auth.user.can.edit.organizations">{{ organization.address_1 }}</v-subheader>
                <v-subheader class="font-weight-light p-0 pr-2" v-else>{{ company.address_1 }} {{ company.address_2 }}</v-subheader>
                <v-divider vertical></v-divider>
                <v-subheader class="font-weight-light p-0 pl-2" v-if="$page.auth.user.can.edit.organizations">
                    {{organization.city}}, {{organization.state}}, {{organization.zip }}
                </v-subheader>
                <v-subheader class="font-weight-light p-0 pl-2" v-else>
                    {{company.city}}, {{company.state}}, {{company.zip }}
                </v-subheader>
            </v-row>
        </v-col>
        <v-col>
            <v-row justify="end" align="center">
                <slot></slot>
            </v-row>
        </v-col>
    </v-row>
</template>

<script>
    export default {
        name: "OrganizationHeader",
        props: {
            organization: {
                type: Object,
                required: true
            },
            company: {
                type: Object,
                required: true
            },
        }
    }
</script>
