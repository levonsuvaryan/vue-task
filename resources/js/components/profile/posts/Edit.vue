<template>
    <div>
        <profile-navigation></profile-navigation>

        <loader v-if="!loaded"></loader>

        <h2 class="title text-center">Edit Post #{{ id }}</h2>

        <form v-show="loaded" method="POST" @submit.prevent="update">
            <div class="form-group">
                <label for="title">Title *</label>
                <input v-model.lazy="title" type="text" id="title" class="form-control" :class="{'is-invalid': errors.has('title')}" required>

                <span v-if="errors.has('title')" class="invalid-feedback">
                    <strong>{{ errors.first('title') }}</strong>
                </span>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea v-model.lazy="description" id="description" rows="9" class="form-control"
                          :class="{'is-invalid': errors.has('description')}" required>
                </textarea>

                <span v-if="errors.has('description')" class="invalid-feedback">
                    <strong>{{ errors.first('description') }}</strong>
                </span>
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
                loaded: false,
                busy: false,
                title: '',
                description: '',
            }
        },

        methods: {
            fetch () {
                axios.get(`/api/posts/${this.id}`).then(response => {
                    this.title = response.data.data.title;
                    this.description = response.data.data.description;
                    this.loaded = true;

                }).catch(error => {
                    this.$router.push({name: 'profile'});
                });
            },

            update () {
                this.busy = true;

                axios.put(`/api/profile/posts/${this.id}`, {
                    title: this.title,
                    description: this.description

                }).then(response => {
                    this.errors.forget();
                    this.$router.push({
                        name: 'profile.posts.show',
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

        created () {
            this.fetch();
        },

        watch: {
            $route (to) {
                this.id = to.params['id'];
            }
        },
    }
</script>