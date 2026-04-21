<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import '../styles/home.css'

const router = useRouter()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const brands = ref([])
const isLoading = ref(false)
const error = ref(null)

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  isLoading.value = true
  try {
    brands.value = await brandStore.fetchAllBrands()
  } catch (err) {
    error.value = 'Failed to load brands'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const handleBrandClick = (brandId) => {
  router.push(`/brand/${brandId}`)
}
</script>

<template>
  <BaseLayout>
    <div class="home-container">
      <h1 class="home-title">Car Brands</h1>

      <div v-if="isLoading" class="loading">
        <p>Loading brands...</p>
      </div>

      <div v-else-if="error" class="error">
        <p>{{ error }}</p>
      </div>

      <div v-else class="brands-grid">
        <div
          v-for="brand in brands"
          :key="brand.id"
          class="brand-card"
          @click="handleBrandClick(brand.id)"
        >
          <div class="brand-card-content">
            <h2>{{ brand.name }}</h2>
            <p v-if="brand.description" class="brand-description">{{ brand.description }}</p>
            <p class="brand-hover">Click to view models →</p>
          </div>
        </div>
      </div>

      <div v-if="brands.length === 0 && !isLoading" class="empty-state">
        <p>No brands available</p>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: index
meta:
  title: Főoldal
</route>
