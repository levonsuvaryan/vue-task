import VueRouter from 'vue-router'

const routes = [
    // BLOG
    {
        path: '/',
        component: require('./components/blog/PostList.vue').default,
        name: 'blog',
        meta: {requires: {}}
    },
    {
        path: '/post/:id',
        component: require('./components/blog/PostView.vue').default,
        name: 'post',
        meta: {requires: {}}
    },

    // AUTH
    {
        path: '/register',
        component: require('./components/auth/Register.vue').default,
        name: 'register',
        meta: {requires: {guest: true}}
    },
    {
        path: '/login',
        component: require('./components/auth/Login.vue').default,
        name: 'login',
        meta: {requires: {guest: true}}
    },
    {
        path: '/logout',
        component: require('./components/auth/Logout.vue').default,
        name: 'logout',
        meta: {requires: {auth: true}}
    },

    // PROFILE
    {
        path: '/profile',
        component: require('./components/profile/Home.vue').default,
        name: 'profile',
        meta: {requires: {auth: true}}
    },

    // POSTS
    {
        path: '/profile/posts',
        component: require('./components/profile/posts/Index.vue').default,
        name: 'profile.posts',
        meta: {requires: {auth: true}}
    },
    {
        path: '/profile/posts/create',
        component: require('./components/profile/posts/Create.vue').default,
        name: 'profile.posts.create',
        meta: {requires: {auth: true}}
    },
    {
        path: '/profile/posts/:id',
        component: require('./components/profile/posts/Show.vue').default,
        name: 'profile.posts.show',
        meta: {requires: {auth: true}}
    },
    {
        path: '/profile/posts/:id/edit',
        component: require('./components/profile/posts/Edit.vue').default,
        name: 'profile.posts.edit',
        meta: {requires: {auth: true}}
    },
    {
        path: '/profile/posts/:id/images',
        component: require('./components/profile/posts/Images.vue').default,
        name: 'profile.posts.images',
        meta: {requires: {auth: true}}
    },

    // USERS
    {
        path: '/profile/users',
        component: require('./components/profile/users/Index.vue').default,
        name: 'profile.users',
        meta: {requires: {auth: true}},
    },
    {
        path: '/profile/users/:id',
        component: require('./components/profile/users/Show.vue').default,
        name: 'profile.users.show',
        meta: {requires: {auth: true}}
    },
    {
        path: '/profile/users/:id/edit',
        component: require('./components/profile/users/Edit.vue').default,
        name: 'profile.users.edit',
        meta: {requires: {auth: true}}
    },

    // ERRORS
    {
        path: '*',
        component: require('./components/errors/Error404.vue').default,
        meta: {requires: {}}
    },
];

const vueRouter = new VueRouter({
    routes,
    linkActiveClass: 'active',
    mode: 'history',
});

vueRouter.beforeEach((to, from, next) => {
    const requires = to.meta.requires;

    if (requires.auth) {

        if ($auth.isAuthenticated()) {
            next();

        } else {
            vueRouter.push({name: 'login'});
        }
    }

    else if (requires.guest) {

        if ($auth.isGuest()) {
            next();

        } else {
            vueRouter.push({name: 'profile'});
        }
    }

    else {
        next();
    }
});

export default vueRouter