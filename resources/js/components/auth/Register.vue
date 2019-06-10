<template>
    <div>
        <div class="row justify-content-center">
            <div class="col-md-5">
                <h2 class="title text-center">Register</h2>

                <form method="POST" @submit.prevent="register">
                    <div class="form-group">
                        <label for="name">Display Name *</label>
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

                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input v-model="password" type="password" id="password" class="form-control" :class="{'is-invalid': errors.has('password')}" required>

                        <span v-if="errors.has('password')" class="invalid-feedback">
                            <strong>{{ errors.first('password') }}</strong>
                        </span>
                    </div>

                    <div class="form-group">
                        <label for="confirmation">Password confirmation *</label>
                        <input v-model="password_confirmation" type="password" id="confirmation" class="form-control" :class="{'is-invalid': errors.has('password')}" required>
                    </div>

                    <div class="form-group-sm text-right">
                        <button type="submit" class="btn btn-dark" :disabled="busy">
                            <i v-if="busy" class="fa fa-spinner fa-spin"></i> Register
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
    import Errors from '../../modules/Errors'

    export default {
        data () {
            return {
                errors: new Errors(),
                busy: false,
                name: '',
                email: '',
                password: '',
                password_confirmation: '',
            };
        },

        methods: {
            register () {
                this.busy = true;

                axios.post('/api/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,

                }).then(response => {
                    this.errors.forget();
                    this.$router.push({name: 'login'});
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.errors.put(error.response.data);
                    this.busy = false;
                });
            },
        },
    }
</script>