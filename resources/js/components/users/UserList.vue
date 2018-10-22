<template>
  <div class="user-list">
    <div class="row">
      <div v-for="user in users"
        :key="user.id" 
        class="col-4">
        <div class="card mb-3">
          <div class="card-body">
            <label>{{ user.lastname }} , {{ user.name }}</label>
          </div>
          <div class="card-footer text-right">
            <a href="javascript:void(0)"
              @click="removeUser(user.id)" 
              class="btn btn-danger">
              Borrar
            </a>
            <a href="javascript:void(0)"
              @click="editUser(user)" 
              class="btn btn-info">
              Editar
            </a>
          </div>
        </div>
      </div>
    </div>
    <!-- paginator -->
    <nav class="mt-3">
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
      users: Array,
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