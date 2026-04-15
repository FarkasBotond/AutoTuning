<script setup>
import Navbar from '@/components/Navbar.vue'
</script>

<template>
  <Navbar />
  <main class="main-content">
    <RouterView />
  </main>
</template>

<style>
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue',
    Arial, sans-serif;
}

.main-content {
  min-height: calc(100vh - 60px);
  background-color: #f5f5f5;
}
</style>
