<template>
    <div>
        <ul class="nav nav-tabs mb-3">

            <li class="nav-item">
                <router-link tag="a" exact :to="{name: 'profile'}" class="nav-link">
                     Profile
                </router-link>
            </li>

            <li class="nav-item">
                <router-link tag="a" exact :to="{name: 'profile.posts'}" class="nav-link">
                     Posts
                </router-link>
            </li>

            <li v-if="authUserIsAdmin()" class="nav-item">
                <router-link tag="a" exact :to="{name: 'profile.users'}" class="nav-link">
                     Users
                </router-link>
            </li>

        </ul>
    </div>
</template>

<script>
    export default {
        methods: {
            authUserIsAdmin () {
                return this.$store.getters.authUserIsAdmin;
            }
        }
    }
</script>