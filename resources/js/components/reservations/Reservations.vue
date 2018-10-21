<template>
  <div>
    <h3 class="p-3 text-center">
      Sistema de Reservas
    </h3>

    <div class="mb-3">
      <router-link :to="{ name: 'new_reservation' }"
        class="btn btn-success">
        Nueva reserva
      </router-link>
    </div>
    
    <reservation-list 
      :reservations="reservations"
      :pagination="pagination"
      @prev="fetchReservations"
      @next="fetchReservations"></reservation-list>
  </div>
</template>

<script> 
  export default {
    data () {
      return {
        reservations: [],
        pagination: {}
      }
    },
    created() {
      this.fetchReservations();
    },
    methods: {
      fetchReservations (pageUrl) {
        pageUrl = pageUrl || 'reservations'
        axios.get(pageUrl)
        .then(response => {
          console.log('response', response);
          let meta = response.data.meta;
          let links = response.data.links;
          this.reservations = response.data.data;
          this.pagination = {
            current_page: meta.current_page,
            last_page: meta.last_page,
            next_page_url: links.next,
            prev_page_url: links.prev
          }
        })
        .catch(error => {
          console.error('error', error);
        })
      }
    }
  }
</script>

<style>
.justify-center {
  justify-content: center;
}
</style>