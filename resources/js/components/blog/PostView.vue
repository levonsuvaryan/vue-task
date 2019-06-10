<template>
    <div>
        <div class="row">
            <div class="col-md-12 mb-3">
                <div class="blog-border-left">
                    <div class="row">
                        <div class="col-md-5">
                            <div id="post-slider" class="carousel slide" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    <li v-for="(image, index) in post.images"
                                        data-target="#post-slider"
                                        :data-slide-to="index"
                                        :class="{'active': image.is_main}">
                                    </li>
                                </ol>

                                <div class="carousel-inner">
                                    <div v-for="image in post.images"
                                         class="carousel-item"
                                         :class="{'active': image.is_main}">
                                        <img class="d-block w-100" :src="image.path">
                                    </div>
                                </div>

                                <a class="carousel-control-prev" href="#post-slider" role="button" data-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next" href="#post-slider" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>

                            <button @click.prevent="backToPosts" class="btn btn-sm btn-secondary mt-3">
                                <i class="fa fa-arrow-left"></i> Back to posts
                            </button>
                        </div>

                        <div class="col-md-7">
                            <h3>{{ post.title }}</h3>

                            <p>
                                <span class="mr-3"><i class="fa fa-user"></i> {{ post.user.name }}</span>
                                <span class="mr-3"><i class="fa fa-calendar"></i> {{ post.created_at }}</span>
                            </p>

                            <p>{{ post.description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="blog-border-left mb-3">
                    <h5 class="mb-3">Comments (<span v-text="commentsCount"></span>)</h5>

                    <form v-if="isAuthenticated" id="comment-form" method="POST" @submit.prevent="writeComment">

                        <div class="form-group">
                            <label for="comment">Write your comment</label>

                            <textarea v-model="comment" id="comment" rows="5" placeholder="Comment here ..."
                                      class="form-control" :class="{'is-invalid': errors.has('comment')}" required>
                            </textarea>

                            <span v-if="errors.has('comment')" class="invalid-feedback">
                                <strong>{{ errors.first('comment') }}</strong>
                            </span>
                        </div>

                        <div class="form-group-sm text-right">
                            <button type="submit" class="btn btn-dark" :disabled="busy">
                                <i v-if="busy" class="fa fa-spinner fa-spin"></i> Store
                            </button>
                        </div>
                    </form>

                    <div v-else="isGuest" class="alert alert-info">
                        To write comment you need to authenticate.
                    </div>

                    <div v-if="commentsCount > 0" class="mt-4">

                        <div class="border pl-3 pr-3 pt-2 pb-2 mb-3" v-for="item in post.comments">
                            <p class="m-0 mb-2">
                                <span class="mr-3"><i class="fa fa-user"></i> {{ item.user.name }}</span>
                                <span class="mr-3"><i class="fa fa-clock-o"></i> {{ item.created_at }}</span>
                            </p>

                            <p class="m-0">{{ item.body }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Errors from '../../modules/Errors';

    export default {
        data () {
            return {
                id: this.$route.params['id'],

                post: {
                    user: {},
                    images: [],
                    comments: [],
                },

                errors: new Errors(),
                busy: false,
                comment: ''
            }
        },

        computed: {
            commentsCount () {
                return this.post.comments.length;
            },
            isAuthenticated () {
                return $auth.isAuthenticated();
            },
            isGuest () {
                return $auth.isGuest();
            }
        },

        methods: {
            fetch () {
                axios.get(`/api/posts/${this.id}`).then(response => {
                    this.post = response.data.data;
                });
            },

            writeComment () {
                this.busy = true;

                axios.post(`/api/posts/${this.id}/comments`, {
                    comment: this.comment

                }).then(response => {
                    $('#comment-form').hide(500);
                    this.errors.forget()
                    this.comment = '';
                    let storedComment = response.data.data;
                    this.post.comments.unshift(storedComment);
                    this.$toaster.success('Comment stored successfully!');
                    this.busy = false;

                }).catch(error => {
                    this.errors.put(error.response.data);
                    this.busy = false;
                });
            },

            backToPosts () {
                this.$router.push({name: 'blog'});
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

