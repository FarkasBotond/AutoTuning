<script setup>
import StickyCartButton from '@/components/StickyCartButton.vue'
</script>

<template>
  <main class="main-content">
    <RouterView />
    <StickyCartButton />
  </main>
</template>

<style scoped>
.main-content {
  min-height: 100vh;
}
</style>
