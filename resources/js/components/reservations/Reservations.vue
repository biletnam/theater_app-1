<template>
  <div>
    <h3 class="p-3 text-center">
      Sistema de Reservas
    </h3>
    
    <reservation-list 
      :reservations="reservations"
      :pagination="pagination"></reservation-list>

  </div>
</template>

<script> 
  export default {
    data () {
      return {
        reservations: [],
        pagination: null
      }
    }
  }
</script>

<style>
.justify-center {
  justify-content: center;
}
</style>