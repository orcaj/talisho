<template>
    <v-card>
        <v-toolbar dark color="blue darken-5">
            <v-toolbar-title>
                Edit {{ designDocument.name }}
            </v-toolbar-title>
        </v-toolbar>

        <v-card-text>
            <v-form v-model="valid" ref="form">
                <div class="font-weight-bold subtitle-1 pt-4">Name</div>

                <v-text-field
                    v-model="name"
                    class="pb-2"
                    color="blue darken-5"
                    placeholder="Name..."
                    :rules="[
                        (val) => !!val || 'Name is required'
                    ]"
                />
            </v-form>
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="secondary"
                text
                class="mx-0 px-2"
                @click="confirmExitDialog = true"
            >
                Cancel
            </v-btn>
            <v-btn
                class="mx-4 px-2 white--text"
                color="blue darken-5"
                @click="edit"
            >
                Update Name
            </v-btn>
        </v-card-actions>

        <confirmation-modal
            :dialog="confirmExitDialog"
            @close="confirmExitDialog = false"
            @confirm="$emit('close')"
        />

    </v-card>
</template>

<script>
    import ConfirmationModal from "../Documentation/ConfirmationModal";
    export default {
        components: {
            ConfirmationModal
        },

        props: {
            designDocument: {
                type: Object,
                required: true
            }
        },

        data() {
            return {
                name: this.designDocument.name,
                valid: false,
                confirmExitDialog: false
            }
        },

        methods: {
            edit() {
                if (this.$refs.form.validate()) {
                    this.$inertia.put(this.route('organizations.projects.design-documents.update', [this.$page.organization.id, this.$page.project.id, this.designDocument.id]), {
                        'name': this.name
                    }, {
                        preserveScroll: true,
                        preserveState: true,
                    });

                    this.$emit('close');
                }
            }
        }

    }
</script>
