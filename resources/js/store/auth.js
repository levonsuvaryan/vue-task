export default {
    state: {
        authUser: {},
    },

    getters: {
        authUserIsAdmin (state) {
            return state.authUser.role === 'admin';
        },

        authUserName (state) {
            return state.authUser.name;
        },
    },

    mutations: {
        saveUser (state, user) {
            state.authUser = user;
        },
        forgetUser (state) {
            state.authUser = {};
        }
    },

    actions: {
        authUserRequest ({commit}) {
            axios.get('/api/user').then(response => {
                commit('saveUser', response.data.data);
            });
        }
    },

}