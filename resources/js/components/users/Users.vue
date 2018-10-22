<template>
  <div>
    <h3 class="p-3 text-center">
      Administración de usuarios
    </h3>

    <div v-if="!visibleForm"
      class="mb-4 text-center">
      <button type="button"
        class="btn btn-success"
        @click="showForm">
        Nuevo usuario
      </button>
    </div>

    <user-form 
      v-if="visibleForm"
      class="mb-3"
      :user="user"
      :isEditing="edit"
      @saved="onSavedUser"
      @cancelled="cancelForm"
      ></user-form>

    <user-list 
      :users="users"
      :pagination="pagination"
      @edited="startEdition"
      @removed="confirmRemoving"
      @prev="fetchUsers"
      @next="fetchUsers"></user-list>

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
        edit: false,
        visibleForm: false
      }
    },
    created () {
      this.fetchUsers();
    },
    methods: {
      showForm () {
        this.visibleForm = !this.visibleForm;
      },
      cancelForm () {
        this.user.id = null;
        this.user.name = null;
        this.user.lastname = null;
        this.visibleForm = !this.visibleForm;
      },
      fetchUsers (pageUrl) {
        pageUrl = pageUrl || 'users'
        axios.get(pageUrl)
          .then(response => {
            let meta = response.data.meta;
            let links = response.data.links;
            this.users = response.data.data;
            this.pagination = {
              current_page: meta.current_page,
              last_page: meta.last_page,
              next_page_url: links.next,
              prev_page_url: links.prev
            };
          })
          .catch(error => {
            alert('Ha ocurrido un inconveniente en el servidor.');
          })
      },
      onSavedUser (message) {
        this.user.id = null;
        this.user.name = null;
        this.user.lastname = null;
        alert(message); // @todo: change this
        this.fetchUsers();
        this.visibleForm = !this.visibleForm
      },
      startEdition (user) {
        this.edit = true
        this.user.id = user.id
        this.user.name = user.name
        this.user.lastname = user.lastname
        this.visibleForm = !this.visibleForm
      },
      confirmRemoving (id) {
        if (confirm('¿Está seguro que desea eliminar al usuario?')) {
          axios.delete('user/' + id)
            .then(response => {
              var response = response.data;
              if (response.error) {
                alert(response.message);
                return;
              }
              this.fetchUsers();
            })
            .catch(error => {
              alert('Ha ocurrido un inconveniente en el servidor.');
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