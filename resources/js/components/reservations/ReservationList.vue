<template>
  <div class="reservation-list">
    <div v-if="reservations"
      v-for="reservation in reservations"
      :key="reservation.id"
      class="card mb-3">
      
    </div>
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
      editUser(user) {
        this.$emit('edited', user);
      },
      removeUser(id) {
        this.$emit('removed', id);
      }
    }
  }
</script>

