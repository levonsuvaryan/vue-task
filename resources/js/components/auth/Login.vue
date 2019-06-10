<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h2 class="title text-center">Login</h2>

                <form method="POST" @submit.prevent="login">

                    <div class="form-group">
                        <label for="email">Email address *</label>
                        <input v-model.lazy="email" type="email" id="email" class="form-control" :class="{'is-invalid': errors.has('email')}" required>

                        <span v-if="errors.has('email')" class="invalid-feedback">
                            <strong>{{ errors.first('email') }}</strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input v-model.lazy="password" type="password" id="password" class="form-control" :class="{'is-invalid': errors.has('password')}" required>

                        <span v-if="errors.has('password')" class="invalid-feedback">
                            <strong>{{ errors.first('password') }}</strong>
                        </span>
                    </div>

                    <div class="form-group-sm text-right" :disabled="busy">
                        <button type="submit" class="btn btn-dark">
                            <i v-if="busy" class="fa fa-spinner fa-spin"></i> Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Errors from '../../modules/Errors';

    export default {
        data () {
            return {
                errors: new Errors(),
                busy: false,
                email: '',
                password: '',
            };
        },

        methods: {
            login () {
                this.busy = true;

                axios.post('/api/login', {
                    email: this.email,
                    password: this.password

                }).then(response => {
                    this.errors.forget();

                    $auth.login(response.data.access_token)
                    this.$store.dispatch('authUserRequest');

                    this.$router.push({name: 'profile'});
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    if (error.response.status === 422) {
                        this.errors.put(error.response.data);
                    } else {
                        this.$toaster.error(error.response.data.message);
                    }
                    this.busy = false;
                });
            },
        },
    }
</script>