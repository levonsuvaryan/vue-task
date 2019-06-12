<template>
    <div>
        <profile-navigation></profile-navigation>

        <loader v-if="!loaded"></loader>

        <div v-show="loaded" class="row mb-3">
            <div class="col-md-8">
                <h3 class="title">Post #{{ id }} - {{ post.title }}</h3>
            </div>

            <div class="col-md-4 text-right">
                <router-link :to="{name: 'profile.posts.images', params: {id: id}}" class="btn btn-success mr-3">
                    <i class="fa fa-image"></i> Images
                </router-link>

                <router-link :to="{name: 'profile.posts.edit', params: {id: id}}" class="btn btn-warning mr-3">
                    <i class="fa fa-edit"></i> Edit
                </router-link>

                <button @click.prevent="destroy" class="btn btn-danger" :disabled="busy">
                    <i v-if="busy" class="fa fa-spinner fa-spin"></i>
                    <i class="fa fa-trash-o"></i> Delete
                </button>
            </div>
        </div>

        <div v-show="loaded" class="row">
            <div class="col-md-4">
                <img :src="post.image.path" class="img-fluid img-thumbnail">
            </div>

            <div class="col-md-8">
                <p>
                    <span class="mr-3"><i class="fa fa-user"></i> {{ post.user.name }}</span>
                    <span class="mr-3"><i class="fa fa-calendar"></i> {{ post.created_at }}</span>
                </p>

                <p>{{ post.description }}</p>
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
                post: {
                    image: {},
                    user: {},
                }
            }
        },

        methods: {
            fetch () {
                axios.get(`/api/profile/posts/${this.id}`).then(response => {
                   this.post = response.data.data;
                   this.loaded = true;

                }).catch(error => {
                    this.$router.push({name: 'profile'});
                });
            },

            destroy () {
                this.busy = true;

                axios.delete(`/api/profile/posts/${this.id}`).then(response => {
                    this.$router.push({name: 'profile.posts'});
                    this.$toaster.success(response.data.message);
                    this.busy = false;

                }).catch(error => {
                    this.busy = false;
                });
            },
        },

        created () {
            this.fetch();
        },

        watch: {
            $route (to) {
                this.id = to.params['id'];
            }
        }
    }
</script>