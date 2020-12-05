<template>
    <user-form
        @submit="submit"
        :user="user"
        :company="company"
        :errors="errors"
    >
    </user-form>
</template>

<script>
    import UserForm from "../../Shared/UserForm";
    import Layout from "../../Shared/Layout";

    export default {
        layout: Layout,
        components: {
            UserForm
        },
        props: {
            user: {
                type: Object,
                required: true
            },
            company: {
                type: Object,
                required: true
            }
        },
        data() {
            return {
                errors: {},
                showConfirmation: false
            }
        },
        methods: {
            async submit(value) {
                this.$inertia.put(this.route('users.update', {user: this.user.id}), value);
            }
        }
    }
</script>
