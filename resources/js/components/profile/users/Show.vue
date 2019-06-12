<template>
    <div>
        <profile-navigation></profile-navigation>

        <loader v-if="!loaded"></loader>

        <div class="row mb-3">
            <div class="col-md-7">
                <h3 class="title">User #{{ id }}</h3>
            </div>

            <div v-show="loaded" class="col-md-5 text-right">

                <button v-if="isUser" @click.prevent="setAdmin" class="btn btn-primary mr-3" :disabled="busy">
                    <i v-if="busy" class="fa fa-spinner fa-spin"></i> Set Admin
                </button>

                <button v-if="isAdmin" @click.prevent="setUser" class="btn btn-secondary mr-3" :disabled="busy">
                    <i v-if="busy" class="fa fa-spinner fa-spin"></i> Set User
                </button>

                <router-link :to="{name: 'profile.users.edit', params: {id: id}}" class="btn btn-warning mr-3">
                    <i class="fa fa-edit"></i> Edit
                </router-link>

                <button @click.prevent="destroy" class="btn btn-danger" :disabled="busy">
                    <i v-if="busy" class="fa fa-spinner fa-spin"></i>
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </div>
        </div>

        <div v-show="loaded" class="row">
            <div class="col-md-7">
                <table class="table border">
                    <tr>
                        <th>Name</th>
                        <td>{{ user.name }}</td>
                    </tr>
                    <tr>
                        <th>Email address</th>
                        <td>{{ user.email }}</td>
                    </tr>
                    <tr>
                        <th>Registration date</th>
                        <td>{{ user.registration_date }}</td>
                    </tr>
                    <tr>
                        <th>Role</th>
                        <td>
                            <span v-if="isUser" class="badge badge-secondary">{{ user.role.name }}</span>
                            <span v-else="isAdmin" class="badge badge-primary">{{ user.role.name }}</span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="col-md-5">
                <table class="table border">
                    <tr v-for="item in user.statistics">
                        <th>{{ item.name }}</th>
                        <td>{{ item.value }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data () {
            return {
                id: this.$route.params['id'],
                loaded: false,
                busy: false,
                user: {
                    role: {},
                    statistics: []
                }
            }
        },

        computed: {
            isAdmin () {
                return this.user.role.type === 'admin';
            },

            isUser () {
                return this.user.role.type === 'user';
            },
        },

        methods: {
            fetch () {
                axios.get(`/api/profile/users/${this.id}`).then(response => {
                    this.user = response.data.data;
                    this.loaded = true;
                });
            },

            destroy () {
                this.busy = true;

                axios.delete(`/api/profile/users/${this.id}`).then(response => {
                    this.$router.push({name: 'profile.users'});
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.busy = false;
                });
            },

            setAdmin () {
                this.busy = true;

                axios.post(`/api/profile/users/${this.id}/admin`).then(response => {
                    this.user.role = response.data.role;
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.busy = false;
                });
            },

            setUser () {
                this.busy = true;

                axios.post(`/api/profile/users/${this.id}/user`).then(response => {
                    this.user.role = response.data.role;
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.busy = false;
                });
            },
        },

        watch: {
            $route (to) {
                this.id = to.params['id'];
            }
        },

        beforeRouteEnter (to, from, next) {
            next(vm => {
                if (! vm.$store.getters.authUserIsAdmin) {
                    vm.$router.push({name: 'profile'});

                } else {
                    vm.fetch();
                }
            });
        }
    }
</script>