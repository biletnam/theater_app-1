import Vue from 'vue'
import VueRouter from 'vue-router'

import Reservations from './components/reservations/Reservations'
import ReservationForm from './components/reservations/ReservationForm'
import Users from './components/users/Users'

Vue.use(VueRouter);

export default new VueRouter({
    routes: [
      {
        path: '/',
        name: 'reservations',
        component: Reservations
      },
      {
        path: '/new-reservation',
        name: 'new_reservation',
        component: ReservationForm
      },
      {
        path: '/reservation/{id}',
        name: 'reservation',
        component: ReservationForm
      },
      {
        path: '/users',
        name: 'users',
        component: Users
      },
    ]
});
