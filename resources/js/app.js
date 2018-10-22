/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

axios.defaults.baseURL = 'api/';

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('navigator', require('./components/Navigator.vue'));

Vue.component('reservations', require('./components/reservations/Reservations.vue'));
Vue.component('reservation-list', require('./components/reservations/ReservationList.vue'))
Vue.component('reservation-form', require('./components/reservations/ReservationForm.vue'))

Vue.component('users', require('./components/users/Users.vue'));
Vue.component('user-list', require('./components/users/UserList.vue'));
Vue.component('user-form', require('./components/users/UserForm.vue'));

import router from './routes'

const app = new Vue({
    el: '#app',
    router,
});
