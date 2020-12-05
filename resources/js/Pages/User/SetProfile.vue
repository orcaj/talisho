<template>
    <user-form
        @submit="submit"
        :user="user"
        :company="company"
    >
    </user-form>
</template>

<script>
    import FormValidation from "../../mixins/FormValidation";
    import UserForm from "../../Shared/UserForm";
    import Layout from "../../Shared/Layout";

    export default {
        layout: Layout,
        components: {
            UserForm
        },
        mixins: [
            FormValidation
        ],
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
        methods: {
            async submit(value) {
                const urlParams = new URLSearchParams(window.location.search);
                const url = `/welcome/${this.user.id}?expires=${urlParams.get('expires')}&signature=${urlParams.get('signature')}`;

                this.$inertia.post(url, value);
            }
        }
    }
</script>

