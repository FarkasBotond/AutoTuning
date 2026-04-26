<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useModelStore } from '@stores/modelStore'
import { useBrandStore } from '@stores/brandStore'

const router = useRouter()
const route = useRoute()
const modelStore = useModelStore()
const brandStore = useBrandStore()

const model = ref(null)
const brand = ref(null)
const isLoading = ref(false)
const error = ref(null)

onMounted(async () => {
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
    <div class="mx-auto max-w-5xl px-4 py-8 md:px-6">
      <div class="mb-6 flex items-center gap-2 text-sm text-zinc-600">
        <button class="font-medium text-teal-700 transition-colors hover:text-teal-800 hover:underline" @click="goToBrands">Brands</button>
        <span class="text-zinc-400">/</span>
        <button class="font-medium text-teal-700 transition-colors hover:text-teal-800 hover:underline" @click="goBack">
          {{ brand?.name || 'Brand' }}
        </button>
        <span class="text-zinc-400">/</span>
        <span class="font-medium text-zinc-900">{{ model?.name || 'Model' }}</span>
      </div>

      <button class="btn-muted mb-8 px-5 py-2.5" @click="goBack">
        ← Back to Models
      </button>

      <div v-if="isLoading" class="px-4 py-12 text-center text-lg">
        <p>Loading model details...</p>
      </div>

      <div v-else-if="error" class="rounded-xl border border-red-300 bg-red-50 px-6 py-4 text-center text-lg text-red-700">
        <p>{{ error }}</p>
      </div>

      <div v-else-if="model" class="flex flex-col gap-8">
        <div class="rounded-3xl bg-gradient-to-r from-teal-700 to-cyan-700 px-8 py-10 text-white shadow-lg">
          <div class="flex flex-col gap-2">
            <h1 class="text-4xl font-bold">{{ model.name }}</h1>
            <p v-if="brand" class="text-lg opacity-90">{{ brand.name }}</p>
          </div>
        </div>

        <div class="flex flex-col gap-6 rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">
          <h2 class="mb-2 text-2xl font-semibold text-zinc-900">Model Specifications</h2>
          <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            <div v-if="model.gen" class="flex flex-col gap-1 rounded-lg border-l-4 border-teal-600 bg-zinc-50 p-4">
              <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">Generation:</span>
              <span class="text-xl font-bold text-zinc-900">{{ model.gen }}</span>
            </div>
            <div v-if="model.mod" class="flex flex-col gap-1 rounded-lg border-l-4 border-teal-600 bg-zinc-50 p-4">
              <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">Modification:</span>
              <span class="text-xl font-bold text-zinc-900">{{ model.mod }}</span>
            </div>
            <div v-if="model.startyear" class="flex flex-col gap-1 rounded-lg border-l-4 border-teal-600 bg-zinc-50 p-4">
              <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">Production Start:</span>
              <span class="text-xl font-bold text-zinc-900">{{ model.startyear }}</span>
            </div>
            <div v-if="model.endyear" class="flex flex-col gap-1 rounded-lg border-l-4 border-teal-600 bg-zinc-50 p-4">
              <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">Production End:</span>
              <span class="text-xl font-bold text-zinc-900">{{ model.endyear }}</span>
            </div>
            <div v-if="!model.endyear && model.startyear" class="flex flex-col gap-1 rounded-lg border-l-4 border-teal-600 bg-zinc-50 p-4">
              <span class="text-xs font-semibold uppercase tracking-wide text-zinc-600">Status:</span>
              <span class="text-xl font-bold text-zinc-900">Current Production</span>
            </div>
          </div>
        </div>

        <div class="flex flex-col gap-6 rounded-2xl border border-zinc-200 bg-white p-8 shadow-sm">
          <h2 class="mb-2 text-2xl font-semibold text-zinc-900">Available Tunings</h2>
          <div class="flex flex-col gap-4">
            <div v-for="(tuning, idx) in [
              {
                title: 'Premium Performance Tuning',
                desc: 'Enhance your vehicle\'s performance with our premium tuning package',
                features: ['ECU Optimization', 'Power Increase', 'Better Efficiency']
              },
              {
                title: 'Sport Edition',
                desc: 'Aggressive tuning for sports enthusiasts',
                features: ['Maximum Power', 'Custom Exhaust', 'Suspension Upgrade']
              },
              {
                title: 'Eco Mode',
                desc: 'Optimize fuel efficiency and emissions',
                features: ['Reduced Consumption', 'Eco Friendly', 'Extended Range']
              }
            ]" :key="idx" class="flex flex-col gap-4 rounded-lg border border-zinc-200 p-6 transition-all hover:border-teal-300 hover:shadow-md sm:flex-row sm:items-center sm:justify-between">
              <div class="flex flex-col gap-3 sm:flex-1">
                <h3 class="text-lg font-semibold text-zinc-900">{{ tuning.title }}</h3>
                <p class="text-zinc-600">{{ tuning.desc }}</p>
                <div class="flex flex-wrap gap-2">
                  <span v-for="feature in tuning.features" :key="feature" class="inline-block rounded-full bg-teal-50 px-3 py-1 text-xs font-medium text-teal-700">{{ feature }}</span>
                </div>
              </div>
              <button class="mt-4 whitespace-nowrap rounded-lg bg-teal-700 px-6 py-3 font-semibold text-white transition-all hover:bg-teal-800 hover:shadow-lg active:scale-95 sm:mt-0 sm:ml-6">View Details</button>
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
