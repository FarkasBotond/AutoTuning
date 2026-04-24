<script setup>
</script>

<template>
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
  background-color: #ffffff;
}
</style>
