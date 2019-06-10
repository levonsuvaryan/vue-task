<template>
    <div>
        <profile-navigation></profile-navigation>

        <h2 class="title">Hi, {{ authUserName }}.</h2>

    </div>
</template>

<script>
    export default {
        computed: {
            authUserName () {
                return this.$store.getters.authUserName;
            }
        },
    }
</script>