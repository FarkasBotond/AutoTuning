<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'

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
    <div class="mx-auto max-w-4xl px-8 py-8">
      <h1 class="mb-8 text-center text-4xl font-bold text-gray-900">Car Brands</h1>

      <div v-if="isLoading" class="px-4 py-12 text-center text-lg">
        <p>Loading brands...</p>
      </div>

      <div v-else-if="error" class="px-4 py-12 text-center text-lg text-red-600">
        <p>{{ error }}</p>
      </div>

      <div v-else class="mb-8 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
        <div
          v-for="brand in brands"
          :key="brand.id"
          class="group cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-500 hover:shadow-lg"
          @click="handleBrandClick(brand.id)"
        >
          <div class="flex flex-col gap-2">
            <h2 class="text-xl font-semibold text-gray-900">{{ brand.name }}</h2>
            <p v-if="brand.description" class="text-sm text-gray-600">{{ brand.description }}</p>
            <p class="mt-4 text-sm font-medium text-blue-600 opacity-0 transition-opacity duration-300 group-hover:opacity-100">Click to view models →</p>
          </div>
        </div>
      </div>

      <div v-if="brands.length === 0 && !isLoading" class="px-4 py-12 text-center text-lg text-gray-600">
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
