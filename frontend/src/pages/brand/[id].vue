<script setup>
import { onMounted, ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import BaseLayout from '@layouts/BaseLayout.vue'
import { useBrandStore } from '@stores/brandStore'
import { useModelStore } from '@stores/modelStore'
import { useAuthStore } from '@stores/authStore'

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
    error.value = 'Betöltés nem sikerült!'
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
    <div class="mx-auto max-w-5xl px-4 py-8 md:px-6">
      <button class="btn-muted mb-8 px-5 py-2.5" @click="goBack">
        ← Back to Brands
      </button>

      <div v-if="isLoading" class="px-4 py-12 text-center text-lg">
        <p>Betöltés...</p>
      </div>

      <div v-else-if="error" class="rounded-xl border border-red-300 bg-red-50 px-6 py-4 text-center text-lg text-red-700">
        <p>{{ error }}</p>
      </div>

      <div v-else-if="brand" class="flex flex-col gap-8">
        <div class="rounded-3xl bg-gradient-to-r from-teal-700 to-cyan-700 px-8 py-8 text-white shadow-lg">
          <h1 class="mb-2 text-4xl font-bold">{{ brand.name }}</h1>
          <p v-if="brand.description" class="text-lg opacity-95">{{ brand.description }}</p>
        </div>

        <div class="flex flex-col gap-6">
          <h2 class="text-3xl font-semibold text-zinc-900">Elérhető modellek:</h2>

          <div v-if="filteredModels.length === 0" class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 px-6 py-8 text-center text-zinc-600">
            <p>Nincs elérhető modell ehez a gyártóhoz</p>
          </div>

          <div v-else class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
            <div
              v-for="model in filteredModels"
              :key="model.id"
              class="group cursor-pointer rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-teal-200 hover:shadow-lg"
              @click="handleModelClick(model.id)"
            >
              <div class="flex flex-col gap-2">
                <h3 class="text-xl font-semibold text-zinc-900">{{ model.name }}</h3>
                <p v-if="model.gen" class="text-sm text-zinc-600">Generáció: {{ model.gen }}</p>
                <p v-if="model.mod" class="text-sm text-zinc-600">Modifikáció: {{ model.mod }}</p>
                <p v-if="model.startyear" class="text-sm text-zinc-600">{{ model.startyear }}<span v-if="model.endyear"> - {{ model.endyear }}</span></p>
                <p class="mt-4 text-sm font-medium text-teal-700 opacity-0 transition-opacity duration-300 group-hover:opacity-100">Adatok →</p>
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
