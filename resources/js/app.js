require('./bootstrap');

import Vue from 'vue'
import VueRouter from 'vue-router'
import router from './router'
import store from './store'
import App from './components/App.vue'
import Toaster from 'v-toaster'

Vue.use(VueRouter);
Vue.use(Toaster, {timeout: 5000});

Vue.component('profile-navigation', require('./components/profile/Navigation.vue').default);

Vue.config.productionTip = false;

const app = new Vue({
    el: '#app',
    render: h => h(App),
    router,
    store,
});
