<template>
    <div>
        <profile-navigation></profile-navigation>

        <h3 class="title">Users</h3>

        <table class="table border mb-4">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email address</th>
                <th>Registration date</th>
                <th>Role</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="user in users">
                <td>{{ user.id }}</td>
                <td>
                    <router-link :to="{name: 'profile.users.show', params: {id: user.id}}">
                        {{ user.name }}
                    </router-link>
                </td>
                <td>{{ user.email }}</td>
                <td>{{ user.registration_date }}</td>
                <td>
                    <span v-if="user.role.type === 'user'" class="badge badge-secondary">{{ user.role.name }}</span>
                    <span v-else="user.role.type === 'admin'" class="badge badge-primary">{{ user.role.name }}</span>
                </td>
            </tr>
            </tbody>
        </table>

        <ul v-if="meta.last_page > 1" class="pagination">
            <li class="page-item" :class="{disabled: meta.current_page === 1}">
                <a class="page-link" href="#" @click.prevent="fetch(meta.current_page - 1)">Previous</a>
            </li>

            <li class="page-item" v-for="n in meta.last_page" :class="{active: meta.current_page === n}">
                <a class="page-link" href="#" @click.prevent="fetch(n)">{{ n }}</a>
            </li>

            <li class="page-item" :class="{disabled: meta.current_page === meta.last_page}">
                <a class="page-link" @click.prevent="fetch(meta.current_page + 1)" href="#">Next</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                users: [],
                meta: {},
            }
        },

        methods: {
            fetch (page = 1) {
                axios.get(`/api/profile/users?page=${page}`).then(response => {
                    this.users = response.data.data;
                    this.meta = response.data.meta;
                });
            },
        },

        beforeRouteEnter (to, from, next) {
            next(vm => {
                if (! vm.$store.getters.authUserIsAdmin) {
                    vm.$router.push({name: 'profile'});
                    vm.$toaster.error('Access Denied.');
                }
                else {
                    vm.fetch();
                }
            });
        }
    }
</script>