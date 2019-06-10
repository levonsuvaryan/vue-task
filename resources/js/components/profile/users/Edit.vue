<template>
    <div>
        <profile-navigation></profile-navigation>

        <h2 class="title text-center">Edit User #{{ id }}</h2>

        <form method="POST" @submit.prevent="update">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="name">Name *</label>
                        <input v-model.lazy="name" type="text" id="name" class="form-control" :class="{'is-invalid': errors.has('name')}" required>

                        <span v-if="errors.has('name')" class="invalid-feedback">
                            <strong>{{ errors.first('name') }}</strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="email">Email address *</label>
                        <input v-model.lazy="email" type="email" id="email" class="form-control" :class="{'is-invalid': errors.has('email')}" required>

                        <span v-if="errors.has('email')" class="invalid-feedback">
                            <strong>{{ errors.first('email') }}</strong>
                        </span>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input v-model="password" type="password" id="password" class="form-control" :class="{'is-invalid': errors.has('password')}">

                        <span v-if="errors.has('password')" class="invalid-feedback">
                            <strong>{{ errors.first('password') }}</strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="confirmation">Password confirmation</label>
                        <input v-model="password_confirmation" type="password" id="confirmation" class="form-control" :class="{'is-invalid': errors.has('password')}">
                    </div>
                </div>
            </div>

            <div class="form-group-sm text-right">
                <button type="submit" class="btn btn-warning" :disabled="busy">
                    <i v-if="busy" class="fa fa-spinner fa-spin"></i> Save
                </button>
            </div>
        </form>
    </div>
</template>

<script>
    import Errors from '../../../modules/Errors';

    export default {
        data () {
            return {
                id: this.$route.params['id'],
                errors: new Errors(),
                busy: false,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            }
        },

        methods: {
            fetch () {
                axios.get(`/api/profile/users/${this.id}`).then(response => {
                   this.name = response.data.data.name;
                   this.email = response.data.data.email;
                });
            },

            update () {
                this.busy = true;

                axios.put(`/api/profile/users/${this.id}`, {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation

                }).then(response => {
                    this.errors.forget();
                    this.$router.push({
                        name: 'profile.users.show',
                        params: {id: this.id}
                    });
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.errors.put(error.response.data);
                    this.busy = false;
                });
            }
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
                    vm.$toaster.error('Access Denied');

                } else {
                    vm.fetch();
                }
            });
        }
    }
</script>