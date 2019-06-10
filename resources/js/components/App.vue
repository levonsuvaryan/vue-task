<template>
    <div>
        <div id="flex-layout">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark text-light">
                <div class="container">
                    <a class="navbar-brand">Task</a>

                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navigation">
                        <ul class="navbar-nav mr-auto">

                            <router-link tag="li" exact :to="{name: 'blog'}" class="nav-item">
                                <a class="nav-link"><i class="fa fa-newspaper-o"></i> Blog</a>
                            </router-link>

                        </ul>

                        <ul class="navbar-nav ml-auto">

                            <router-link v-if="isAuthenticated()" tag="li" exact :to="{name: 'profile'}" class="nav-item">
                                <a class="nav-link"><i class="fa fa-user"></i> Profile</a>
                            </router-link>

                            <router-link v-if="isAuthenticated()" tag="li" exact :to="{name: 'logout'}" class="nav-item">
                                <a class="nav-link"><i class="fa fa-unlock"></i> Logout</a>
                            </router-link>

                            <router-link v-if="isGuest()" tag="li" exact :to="{name: 'register'}" class="nav-item">
                                <a class="nav-link"><i class="fa fa-user"></i> Register</a>
                            </router-link>

                            <router-link v-if="isGuest()" tag="li" exact :to="{name: 'login'}" class="nav-item">
                                <a class="nav-link"><i class="fa fa-lock"></i> Login</a>
                            </router-link>

                        </ul>
                    </div>
                </div>
            </nav>

            <main id="flex-content">
                <div class="container">
                    <router-view></router-view>
                </div>
            </main>

            <nav class="navbar navbar-expand-md navbar-dark bg-dark text-light">
                <div class="container">
                    <div class="pull-left">
                        Copyright &copy; 2019 Task.
                    </div>
                </div>
            </nav>
        </div>
    </div>
</template>

<script>
    export default {
        methods: {
            isAuthenticated() {
                return $auth.isAuthenticated();
            },
            isGuest() {
                return $auth.isGuest();
            },
        },

        created () {
            if ($auth.isAuthenticated()) {
                this.$store.dispatch('authUserRequest');
            }
        }
    }
</script>

<style scoped>
     #flex-layout {
        display: flex;
        flex-direction: column;
        min-height: calc(100vh + 1px);
    }

    #flex-content {
        flex: 1;
        padding: 20px 0;
    }
</style>