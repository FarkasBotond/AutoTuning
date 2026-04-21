<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useModelStore } from '@stores/modelStore'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import '../../styles/model-detail.css'

const router = useRouter()
const route = useRoute()
const modelStore = useModelStore()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const model = ref(null)
const brand = ref(null)
const isLoading = ref(false)
const error = ref(null)

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  isLoading.value = true
  try {
    const modelId = route.params.id
    model.value = await modelStore.fetchSingleModel(modelId)
    
    // Fetch the brand for this model
    if (model.value?.brand_id) {
      brand.value = await brandStore.fetchSingleBrand(model.value.brand_id)
    }
  } catch (err) {
    error.value = 'Failed to load model details'
    console.error(err)
  } finally {
    isLoading.value = false
  }
})

const goBack = () => {
  // Go back to the brand detail page if we know the brand_id
  if (model.value?.brand_id) {
    router.push(`/brand/${model.value.brand_id}`)
  } else {
    router.push('/')
  }
}

const goToBrands = () => {
  router.push('/')
}
</script>

<template>
  <BaseLayout>
    <div class="model-detail-container">
      <div class="breadcrumb">
        <button class="breadcrumb-link" @click="goToBrands">Brands</button>
        <span class="breadcrumb-separator">/</span>
        <button class="breadcrumb-link" @click="goBack">
          {{ brand?.name || 'Brand' }}
        </button>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">{{ model?.name || 'Model' }}</span>
      </div>

      <button class="back-button" @click="goBack">← Back to Models</button>

      <div v-if="isLoading" class="loading">
        <p>Loading model details...</p>
      </div>

      <div v-else-if="error" class="error">
        <p>{{ error }}</p>
      </div>

      <div v-else-if="model" class="model-detail">
        <div class="model-header">
          <div class="model-header-content">
            <h1>{{ model.name }}</h1>
            <p v-if="brand" class="model-brand">{{ brand.name }}</p>
          </div>
        </div>

        <div class="model-specs">
          <h2>Model Specifications</h2>
          <div class="specs-grid">
            <div v-if="model.gen" class="spec-item">
              <span class="spec-label">Generation:</span>
              <span class="spec-value">{{ model.gen }}</span>
            </div>
            <div v-if="model.mod" class="spec-item">
              <span class="spec-label">Modification:</span>
              <span class="spec-value">{{ model.mod }}</span>
            </div>
            <div v-if="model.startyear" class="spec-item">
              <span class="spec-label">Production Start:</span>
              <span class="spec-value">{{ model.startyear }}</span>
            </div>
            <div v-if="model.endyear" class="spec-item">
              <span class="spec-label">Production End:</span>
              <span class="spec-value">{{ model.endyear }}</span>
            </div>
            <div v-if="!model.endyear && model.startyear" class="spec-item">
              <span class="spec-label">Status:</span>
              <span class="spec-value">Current Production</span>
            </div>
          </div>
        </div>

        <div class="tunings-section">
          <h2>Available Tunings</h2>
          <div class="tunings-list">
            <div class="tuning-item">
              <div class="tuning-info">
                <h3>Premium Performance Tuning</h3>
                <p class="tuning-description">Enhance your vehicle's performance with our premium tuning package</p>
                <div class="tuning-features">
                  <span class="feature">ECU Optimization</span>
                  <span class="feature">Power Increase</span>
                  <span class="feature">Better Efficiency</span>
                </div>
              </div>
              <button class="tuning-action">View Details</button>
            </div>

            <div class="tuning-item">
              <div class="tuning-info">
                <h3>Sport Edition</h3>
                <p class="tuning-description">Aggressive tuning for sports enthusiasts</p>
                <div class="tuning-features">
                  <span class="feature">Maximum Power</span>
                  <span class="feature">Custom Exhaust</span>
                  <span class="feature">Suspension Upgrade</span>
                </div>
              </div>
              <button class="tuning-action">View Details</button>
            </div>

            <div class="tuning-item">
              <div class="tuning-info">
                <h3>Eco Mode</h3>
                <p class="tuning-description">Optimize fuel efficiency and emissions</p>
                <div class="tuning-features">
                  <span class="feature">Reduced Consumption</span>
                  <span class="feature">Eco Friendly</span>
                  <span class="feature">Extended Range</span>
                </div>
              </div>
              <button class="tuning-action">View Details</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: model-detail
meta:
  title: Model Details
</route>
