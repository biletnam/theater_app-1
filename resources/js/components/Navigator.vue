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

<style scoped>
 nav li * {
   color: #ffffff !important;
 }
 nav li.nav-item a.nav-link {
   margin: auto 9px !important;
   display: inline-block;
 }
 nav li:hover * {
   text-decoration: underline;
 }
 nav li.router-link-active,
 nav li.router-link-exact-active {
   text-decoration: underline !important;
   background-color: #3D91A0;
   color: #fff !important;
   -webkit-border-radius: 6px;
    -moz-border-radius: 6px;
    border-radius: 6px;
 }
</style>