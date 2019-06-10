<template>
    <div>
        <h2 class="title text-center mb-3">Blog</h2>

        <div class="row">
            <div class="col-md-12 mb-3" v-for="post in posts">
                <div class="blog-border-left">
                    <div class="row">
                        <div class="col-md-3">
                            <img class="img-fluid w-100" :src="post.image.path" :title="post.title">
                        </div>

                        <div class="col-md-9">
                            <h4>{{ post.title }}</h4>

                            <p>
                                <span class="mr-3"><i class="fa fa-user"></i> {{ post.user.name }}</span>
                                <span class="mr-3"><i class="fa fa-calendar"></i> {{ post.created_at }}</span>
                            </p>

                            <p>{{ post.description }}</p>

                            <router-link :to="{name: 'post', params: {id: post.id}}" class="btn btn-sm btn-dark">
                                 <i class="fa fa-eye"></i> View
                            </router-link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-12">
            <ul v-if="meta.last_page > 1" class="pagination justify-content-center">
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
    </div>
</template>

<script>
    export default {
        data () {
            return {
                posts: [],
                meta: {},
            }
        },

        methods: {
            fetch (page = 1) {
                axios.get(`/api/posts?page=${page}`).then(response => {
                    this.posts = response.data.data;
                    this.meta = response.data.meta;
                });
            },
        },

        created () {
            this.fetch();
        },
    }
</script>