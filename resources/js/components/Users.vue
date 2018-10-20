<<template>
  <div>
    <h3 class="p-3 text-center">
      Lista de usuarios
    </h3>

    <div v-for="user in users"
      :key="user.id"
      class="card card-body mb-3">
      <p>
        <label>{{ user.lastname }} , {{ user.name }}</label>
      </p>
      <hr>
      <button @click="removeUser(user.id)" class="btn btn-danger">Borrar</button>
    </div>

    <nav aria-label="Lista de usuarios" class="mt-3">
      <ul class="pagination">
        <li class="page-item"
          :class="[{disabled: !pagination.prev_page_url}]">
          <a @click="fetchUsers(pagination.prev_page_url)" class="page-link">
            Anterior
          </a>
        </li>

        <li class="page-item">
          <span class="page-link text-dark disabled">
            {{ pagination.current_page }} de {{ pagination.last_page }}
          </span>
        </li>
        
        <li class="page-item"
          :class="[{disabled: !pagination.next_page_url}]">
          <a @click="fetchUsers(pagination.next_page_url)" class="page-link" href="#">
            Siguiente
          </a>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script>
  export default {
    data () {
      return {
        users: [],
        user: {
          id: null,
          name: null,
          lastname: null
        },
        current_user_id: null,
        pagination: {},
        edit: false
      }
    },
    created () {
      this.fetchUsers();
    },
    methods: {
      fetchUsers (pageUrl) {
        let vm = this
        pageUrl = pageUrl || 'api/users'
        axios.get(pageUrl)
          .then(response => {
            this.users = response.data.data
            console.log('response', response)
            vm.doPagination(response.data.meta, response.data.links)
          })
          .catch(error => {
            console.error('error', error)
          })
      },
      doPagination (meta, links) {
        let pagination = {
          current_page: meta.current_page,
          last_page: meta.last_page,
          next_page_url: links.next,
          prev_page_url: links.prev
        }
        this.pagination = pagination
      },
      removeUser (id) {
        if (confirm('¿Está seguro que desea eliminar al usuario?')) {
          axios.delete('api/user/' + id)
            .then(response => {
              alert();
              this.fetchUsers();
            })
            .catch(error => {
              console.error('error', error)
            })
        }
      }
    }
  }
</script>

<style>
.pagination {
  justify-content: center;
}
</style>