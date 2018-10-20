import Vue from 'vue'
import VueRouter from 'vue-router'

import Reservations from './components/reservations/Reservations'
import Users from './components/users/Users'

Vue.use(VueRouter);

export default new VueRouter({
    mode: 'history',
    routes: [
      {
        path: '/',
        name: 'reservations',
        component: Reservations
      },
      {
        path: '/users',
        name: 'users',
        component: Users
      },
    ]
});
