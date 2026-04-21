<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'
import '../../styles/admin-panel.css'

const router = useRouter()
const authStore = useAuthStore()

if (!authStore.isAuthenticated) {
  router.push('/login')
}

const goToBrands = () => {
  router.push('/admin/brands')
}

const goToModels = () => {
  router.push('/admin/models')
}
</script>

<template>
  <BaseLayout>
    <div class="admin-panel-container">
      <h1 class="admin-title">Admin Panel</h1>
      <p class="admin-subtitle">Select what you want to manage</p>

      <div class="admin-choices">
        <div class="choice-card" @click="goToBrands">
          <div class="choice-icon">🏢</div>
          <h2>Manage Brands</h2>
          <p>Add, edit, or delete car brands</p>
          <button class="choice-button">Go to Brands →</button>
        </div>

        <div class="choice-card" @click="goToModels">
          <div class="choice-icon">🚗</div>
          <h2>Manage Models</h2>
          <p>Add, edit, or delete car models</p>
          <button class="choice-button">Go to Models →</button>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-panel
meta:
  title: Admin Panel
</route>
