<template>
    <div class="flex-center position-ref full-height">
        <div class="code">{{ title }}</div>
        <div class="message" style="padding: 10px;">{{ description }}</div>
    </div>
</template>

<script>
    export default {
        props: {
            status: Number,
        },
        computed: {
            title() {
                return {
                    503: '503',
                    500: '500',
                    404: '404',
                    403: '403',
                }[this.status]
            },
            description() {
                return {
                    503: 'Sorry, we are doing some maintenance. Please check back soon.',
                    500: 'Whoops, something went wrong on our servers.',
                    404: 'Sorry, the page you are looking for could not be found.',
                    403: 'Sorry, this action is unauthorized.',
                }[this.status]
            },
        },
    }
</script>

<style>
    html, body {
        background-color: #fff;
        color: #636b6f;
        font-family: 'Nunito', sans-serif;
        font-weight: 100;
        height: 100vh;
        margin: 0;
    }

    .full-height {
        height: 100vh;
    }

    .flex-center {
        align-items: center;
        display: flex;
        justify-content: center;
    }

    .position-ref {
        position: relative;
    }

    .code {
        border-right: 2px solid;
        font-size: 26px;
        padding: 0 15px 0 15px;
        text-align: center;
    }

    .message {
        font-size: 18px;
        text-align: center;
    }
</style>
