<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useBrandStore } from '@stores/brandStore'
import { useModelStore } from '@stores/modelStore'
import { useAuthStore } from '@stores/authStore'
import '../../styles/brand-detail.css'

const router = useRouter()
const route = useRoute()
const brandStore = useBrandStore()
const modelStore = useModelStore()
const authStore = useAuthStore()

const brand = ref(null)
const allModels = ref([])
const isLoading = ref(false)
const error = ref(null)

const filteredModels = computed(() => {
  if (!brand.value) return []
  return allModels.value.filter(model => model.brand_id === brand.value.id)
})

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  isLoading.value = true
  try {
    const brandId = route.params.id
    brand.value = await brandStore.fetchSingleBrand(brandId)
    allModels.value = await modelStore.fetchAllModels()
  } catch (err) {
    error.value = 'Failed to load brand details'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const goBack = () => {
  router.push('/')
}

const handleModelClick = (modelId) => {
  router.push(`/model/${modelId}`)
}
</script>

<template>
  <BaseLayout>
    <div class="brand-detail-container">
      <button class="back-button" @click="goBack">← Back to Brands</button>

      <div v-if="isLoading" class="loading">
        <p>Loading brand details...</p>
      </div>

      <div v-else-if="error" class="error">
        <p>{{ error }}</p>
      </div>

      <div v-else-if="brand" class="brand-detail">
        <div class="brand-header">
          <h1>{{ brand.name }}</h1>
          <p v-if="brand.description" class="brand-description">{{ brand.description }}</p>
        </div>

        <div class="models-section">
          <h2>Available Models</h2>

          <div v-if="filteredModels.length === 0" class="empty-state">
            <p>No models available for this brand</p>
          </div>

          <div v-else class="models-grid">
            <div
              v-for="model in filteredModels"
              :key="model.id"
              class="model-card"
              @click="handleModelClick(model.id)"
            >
              <div class="model-card-content">
                <h3>{{ model.name }}</h3>
                <p v-if="model.gen" class="model-year">Generation: {{ model.gen }}</p>
                <p v-if="model.mod" class="model-engine">Modification: {{ model.mod }}</p>
                <p v-if="model.startyear" class="model-years">{{ model.startyear }}<span v-if="model.endyear"> - {{ model.endyear }}</span></p>
                <p class="model-hover">View details →</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: brand-detail
meta:
  title: Brand Details
</route>
