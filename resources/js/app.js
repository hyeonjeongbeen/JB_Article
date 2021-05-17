import VueRouter from 'vue-router';
import routes from './routes';
import App from './App.vue';
import store from './store';
import BootstrapVue from 'bootstrap-vue';
import FlashMessage from '@smartweb/vue-flash-message';
import Vue from 'vue';


require('./bootstrap');
window.Vue = require('vue');
Vue.use(BootstrapVue);
Vue.use(FlashMessage);

Vue.use(VueRouter);
Vue.component('spinner', require('vue-simple-spinner'));

const router = new VueRouter({
    routes
});



new Vue({
    router,
    store,
    render: h => h(App)
}).$mount('#app');
