<template>

</template>

<script>
    export default {
        created () {
            axios.post('/api/logout').then(response => {
                $auth.logout();
                this.$store.commit('forgetUser');

                this.$router.push('/');
                this.$toaster.info(response.data.message);
            });
        }
    }
</script>