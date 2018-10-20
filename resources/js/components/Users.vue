<<template>
  <div>
    <h3 class="p-3 text-center">
      Lista de usuarios
    </h3>

    <user-form 
      class="mb-3"
      :user="user"
      :isEditing="edit"
      @saved="onSavedUser"
      ></user-form>

    <div v-for="user in users"
      :key="user.id"
      class="card mb-3">
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

    <nav aria-label="Lista de usuarios" class="mt-3">
      <ul class="pagination justify-center">
        <li class="page-item"
          :class="[{disabled: !pagination.prev_page_url}]">
          <a @click="fetchUsers(pagination.prev_page_url)" class="page-link">
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
          <a @click="fetchUsers(pagination.next_page_url)" class="page-link">
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
        pageUrl = pageUrl || 'users'
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
      onSavedUser (message) {
        this.user.id = null;
        this.user.name = null;
        this.user.lastname = null;
        alert(message); // @todo: change this
        this.fetchUsers();
      },
      editUser (user) {
        this.edit = true
        this.user.id = user.id
        this.user.name = user.name
        this.user.lastname = user.lastname
      },
      removeUser (id) {
        if (confirm('¿Está seguro que desea eliminar al usuario?')) {
          axios.delete('user/' + id)
            .then(response => {
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
.justify-center {
  justify-content: center;
}
</style>