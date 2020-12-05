<template>
    <v-card>
        <v-card-title class="mt-2">{{ title }}</v-card-title>
        <v-card-subtitle v-if="handle_member" align="center"  justify="center">
            {{ handle_member.name }},
            <i v-if="handle_member.can.edit.projects">
                {{ handle_member.organization.name }}
            </i>
            <i v-else>
                {{ handle_member.company.name }}
            </i>
        </v-card-subtitle>

        <v-card-text>

            <div v-if="warningText" class="font-weight-bold py-2">
                {{ warningText }}
            </div>

            <v-autocomplete
                    append-icon="mdi-search"
                    v-model="selected"
                    :loading="loading"
                    :items="items"
                    :search-input.sync="search"
                    :error="error"
                    :error-messages="(error || '') && errorMessage"
                    class="my-6"
                    outlined
                    dense
                    hide-no-data
                    no-filter
                    return-object
                    placeholder="Search"
                    label="Search by Name"
            >
                <template v-slot:item="{ item }">
                    <v-list-item-content>
                        {{ resultDisplay(item) }}
                        <v-list-item-subtitle v-if="item.disabled">{{ excludeMessage }}</v-list-item-subtitle>
                    </v-list-item-content>
                </template>

                <template v-slot:selection="{ item }">
                    {{ resultDisplay(item) }}
                </template>

            </v-autocomplete>


        </v-card-text>

        <v-card-actions class="m-3">
            <v-btn
                    color="secondary"
                    text
                    class="mx-0 px-2"
                    @click="$emit('close')"
            >
                Cancel
            </v-btn>
            <v-btn
                    class="mx-4 px-2 white--text"
                    color="blue darken-5"
                    :disabled="!selected"
                    @click="submit"
            >
                {{ actionText }}
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
    export default {
        name: "UserSearch",
        props: {
            title: {
                type: String,
                required: true
            },
            handle_member: {
                type: Object
            },
            actionText: {
                type: String,
                required: true
            },
            warningText: {
                type: String,
                default: null
            },
            discipline: {
                type: Object,
                required: true
            },
            excludeMessage: {
                type: String,
                default: ''
            }
        },

        data() {
            return {
                tabs: null,
                search: '',
                loading: false,
                selected: null,
                items: []
            }
        },

        methods: {

            find() {
                var result  = [];
                var users  = this.discipline.team.concat(this.discipline.lead);
                for (var i=0; i < users.length; i++) {
                    if (users[i].name.toLowerCase().includes(this.search.toLowerCase())) {
                        if (users[i].isRegistered && users[i].id != this.handle_member.id)
                        {
                            result.push( users[i]);
                        }
                    }
                }

                return result

            },
            fetchData: _.debounce(function () {
                this.items = this.find();

            }, 300),
            resultDisplay(user) {
                return `${user.first_name} ${user.last_name}` ;
            },
            submit() {
                this.search = '';
                this.$emit('submit', this.selected);
                this.selected = null;
                this.items = [];
            }
        },

        computed: {
            error() {
                if (this.search === '' || this.search === null) {
                    return false;
                }

                return this.items.length === 0;
            },
            errorMessage() {
                return 'User not found. See Invite New Users tab in top banner.';
            },
        },

        watch: {
            search(val) {
                if (val === '') {
                    return;
                }

                val && val !== this.select && this.fetchData();
            },
            tabs() {
                this.selected = null;
            }
        },
    }
</script>
<style scoped>
    .v-dialog > .v-card > .v-card__subtitle {
        padding: 24px 24px 30px 30px;
        font-size: 23px;
        font-weight: 700;
        color: rgba(0, 0, 0, 0.8);
    }

    .v-application .py-2 {
        font-weight: 500!important;
        color: black;
    }
    .v-dialog > .v-card > .v-card__text {
        padding: 0 24px 0px 20px;
    }
</style>
