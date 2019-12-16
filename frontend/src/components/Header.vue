<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <router-link class="navbar-brand" to="/">Home</router-link>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item active">
          <router-link to="/add-army" class="nav-link">Add Army</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
    export default {
        name: "Header"
    }
</script>

<style scoped>

</style>
