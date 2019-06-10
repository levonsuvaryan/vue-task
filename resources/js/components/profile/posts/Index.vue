<template>
    <div>
        <profile-navigation></profile-navigation>

        <div class="d-flex justify-content-between mb-3">
            <h3 class="title">Posts</h3>

            <router-link :to="{name: 'profile.posts.create'}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add Post
            </router-link>
        </div>

        <table class="table border mb-4">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Main image</th>
                <th>Created at</th>
                <th>Author</th>
            </tr>
            </thead>

            <tbody>
            <tr v-for="post in posts">
                <td class="align-middle">{{ post.id }}</td>
                <td class="align-middle">
                    <router-link :to="{name: 'profile.posts.show', params: {id: post.id}}">
                        {{ post.title }}
                    </router-link>
                </td>
                <td class="align-middle">
                    <img :src="post.image.path" class="img-fluid" width="70">
                </td>
                <td class="align-middle">{{ post.created_at }}</td>
                <td class="align-middle">{{ post.user.name }}</td>
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
                posts: [],
                meta: {},
            }
        },

        methods: {
            fetch (page = 1) {
                axios.get(`/api/profile/posts?page=${page}`)
                    .then(response => {
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