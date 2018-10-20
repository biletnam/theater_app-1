<template>
  <nav class="navbar navbar-expand-sm navbar-dark bg-info mb-2">
    <div class="container">
      <a href="#" class="navbar-brand">Reservas</a>
    </div>
     <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <router-link tag="li" 
          class="nav-item" 
          :to="{ name: 'reservations' }"
          exact>
          <a class="nav-link">Reservas</a>
        </router-link>
        <router-link tag="li" 
          class="nav-item" 
          :to="{ name: 'users' }">
          <a class="nav-link">Usuarios</a>
        </router-link>
      </ul>
     </div>
  </nav>
</template>