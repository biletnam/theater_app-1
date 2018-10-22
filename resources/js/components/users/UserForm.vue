<template>
  <form @submit.prevent="saveUser">
    <h5 class="text-center">
      <span v-if="isEditing">Edición de</span> 
      <span v-else>Nuevo</span> usuario
    </h5>
    <div class="form-group">
      <label for="name">Nombre(s)</label>
      <input type="text" 
        v-model="user.name"
        class="form-control" 
        id="name"
        ref="input_name"
        placeholder="Nombre(s) del usuario">
    </div>
    <div class="form-group">
      <label for="lastname">Apellido(s)</label>
      <input type="text" 
        v-model="user.lastname"
        class="form-control" 
        id="lastname" 
        placeholder="Apellido(s) del usuario">
    </div>
    
    <div class="text-right">
      <button type="button"
        class="btn btn-secondary"
        @click="cancelForm">
        Cancelar
      </button>
      <button type="submit" class="btn btn-primary">
        Guardar
      </button>
    </div>
  </form>
</template>

<script>
  export default {
    props: {
      user: Object,
      isEditing: Boolean
    },
    created () {
      this.$nextTick(() => this.$refs.input_name.focus())
    },
    methods: {
      cancelForm () {
        this.$emit('cancelled')
      },
      saveUser () {
        if (this.isEditing === false) {
          // add new user
          axios.post('user', JSON.stringify(this.user), {
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then(response => {
              this.$emit('saved', 'El usuario se ha creado exitosamente')
            })
            .catch(error => {
              alert('Ha ocurrido un inconveniente en el servidor.');
            })
        } else {
           // save existing user
            axios.put('user', JSON.stringify(this.user), {
              headers: {
                'Content-Type': 'application/json'
              }
            })
            .then(response => {
              this.$emit('saved', 'El usuario se ha modificado exitosamente')
            })
        }
      }
    }
  }
</script>