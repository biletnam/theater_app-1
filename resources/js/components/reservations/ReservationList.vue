<template>
  <div class="reservation-list">
    <div v-for="reservation in reservations"
      :key="reservation.id"
      class="card mb-3">
      <div class="card-body">
        <h4>{{ reservation.reservation_date }}</h4>
        <small>
          Cantidad de personas: <b>{{ reservation.people_number  }}</b>
        </small>
      </div>
      <div class="card-footer text-right">
        <a href="javascript:void(0)"
          @click="removeReservation(reservation.id)" 
          class="btn btn-danger">
          Borrar
        </a>
        <a href="javascript:void(0)"
          @click="editReservation(reservation)" 
          class="btn btn-info">
          Editar
        </a>
      </div>
    </div>
    <!-- paginator -->
    <nav v-if="pagination" class="mt-3">
      <ul class="pagination justify-center">
        <li class="page-item"
          :class="[{disabled: !pagination.prev_page_url}]">
          <a @click="prevPage(pagination.prev_page_url)" class="page-link">
            Anterior
          </a>
        </li>

        <li class="page-item">
          <span class="page-link text-dark disabled">
            Página {{ pagination.current_page }} de {{ pagination.last_page }}
          </span>
        </li>
        
        <li class="page-item"
          :class="[{disabled: !pagination.next_page_url}]">
          <a @click="nextPage(pagination.next_page_url)" class="page-link">
            Siguiente
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
  export default {
    props: {
      reservations: Array,
      pagination: Object
    },
    methods: {
      prevPage (url) {
        this.$emit('prev', url)
      },
      nextPage (url) {
        this.$emit('next', url)
      },
      editReservation(user) {
        this.$emit('edited', user);
      },
      removeReservation(id) {
        this.$emit('removed', id);
      }
    }
  }
</script>

<style>
.justify-center {
  justify-content: center;
}
</style>
