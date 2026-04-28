<script setup>
import { onMounted, computed, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useModelStore } from '@stores/modelStore'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'

const router = useRouter()
const modelStore = useModelStore()
const brandStore = useBrandStore()
const authStore = useAuthStore()
const selectedBrandId = ref('')
const selectedYearMin = ref(null)
const selectedYearMax = ref(null)
const currentYear = new Date().getFullYear()
const pageError = ref('')

const isAdmin = computed(() => authStore.user?.role === 'admin')

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    pageError.value = ''
    await Promise.all([
      modelStore.fetchAllModels(),
      brandStore.fetchAllBrands()
    ])
  } catch (error) {
    pageError.value = error?.message || 'Nem sikerült betölteni az adatokat.'
  }
})

const handleDelete = async (modelId) => {
  if (confirm('Biztosan szeretné törölni ezt a modellt?')) {
    try {
      pageError.value = ''
      await modelStore.destroy(modelId)
    } catch (error) {
      pageError.value = error?.message || 'A törlés nem sikerült.'
    }
  }
}

const getBrandName = (brandId) => {
  const brand = brandStore.brands.find(b => b.id === brandId)
  return brand?.name || 'Ismeretlen'
}

const brandFilteredModels = computed(() => {
  if (!selectedBrandId.value) {
    return modelStore.models
  }

  const brandId = Number(selectedBrandId.value)
  return modelStore.models.filter(model => model.brand_id === brandId)
})

const yearBounds = computed(() => {
  if (modelStore.models.length === 0) {
    return { min: 1900, max: currentYear }
  }

  const starts = modelStore.models
    .map(model => Number(model.startyear))
    .filter(Number.isFinite)

  const ends = modelStore.models
    .map(model => Number(model.endyear || model.startyear || currentYear))
    .filter(Number.isFinite)

  const min = starts.length ? Math.min(...starts) : 1900
  const max = ends.length ? Math.max(...ends) : currentYear

  return { min, max }
})

watch(yearBounds, (bounds) => {
  if (selectedYearMin.value === null || selectedYearMin.value < bounds.min || selectedYearMin.value > bounds.max) {
    selectedYearMin.value = bounds.min
  }

  if (selectedYearMax.value === null || selectedYearMax.value > bounds.max || selectedYearMax.value < bounds.min) {
    selectedYearMax.value = bounds.max
  }

  if (selectedYearMin.value > selectedYearMax.value) {
    selectedYearMin.value = selectedYearMax.value
  }
}, { immediate: true })

const onMinYearInput = (event) => {
  const value = Number(event.target.value)
  const boundedValue = Math.max(yearBounds.value.min, Math.min(value, yearBounds.value.max))
  selectedYearMin.value = Math.min(boundedValue, selectedYearMax.value ?? boundedValue)
}

const onMaxYearInput = (event) => {
  const value = Number(event.target.value)
  const boundedValue = Math.max(yearBounds.value.min, Math.min(value, yearBounds.value.max))
  selectedYearMax.value = Math.max(boundedValue, selectedYearMin.value ?? boundedValue)
}

const filteredModels = computed(() => {
  return brandFilteredModels.value.filter((model) => {
    const start = Number(model.startyear)
    const end = Number(model.endyear || currentYear)

    if (!Number.isFinite(start)) {
      return false
    }

    const safeEnd = Number.isFinite(end) ? end : start
    return start >= selectedYearMin.value && safeEnd <= selectedYearMax.value
  })
})

const goToCreate = () => {
  router.push('/admin/models/new')
}

const goToEdit = (modelId) => {
  router.push(`/admin/models/${modelId}`)
}
</script>

<template>
  <BaseLayout>
    <div class="mx-auto w-full max-w-[1300px] px-4 py-8 md:px-6">
      <div class="mb-6 flex flex-wrap items-center gap-3">
        <button @click="() => router.push('/admin')" class="btn-muted px-4 py-2">
          ← Admin Panel
        </button>
        <div class="ml-auto flex items-center gap-2">
          <button @click="() => router.push('/admin/brands')" class="btn-muted px-4 py-2 text-xs">
            Gyártók
          </button>
          <button class="btn-primary px-4 py-2 text-xs">Modellek</button>
        </div>
      </div>

      <div class="glass-panel p-6 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
          <h1 class="text-3xl font-extrabold text-zinc-900">Modellek kezelése</h1>
          <button @click="goToCreate" class="btn-primary">
            Új modell hozzáadása
          </button>
        </div>

        <div v-if="pageError" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
          {{ pageError }}
        </div>

        <div v-if="modelStore.isLoading || brandStore.isLoading" class="text-center py-8">
          <p class="text-zinc-500">Betöltés...</p>
        </div>

        <div v-else-if="modelStore.error"
          class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
          {{ modelStore.error }}
        </div>

        <template v-else>
          <div class="mb-6 flex flex-wrap items-end gap-6">
            <div class="w-full max-w-xs">
              <label for="brand-filter" class="mb-2 block text-sm font-semibold text-zinc-700">Szűrés márkára:</label>
              <select id="brand-filter" v-model="selectedBrandId" class="brand-input w-full">
                <option value="">Összes márka</option>
                <option v-for="brand in brandStore.brands" :key="brand.id" :value="String(brand.id)">
                  {{ brand.name }}
                </option>
              </select>
            </div>

            <div class="w-full max-w-xl flex-1">
              <label class="mb-2 block text-sm font-semibold text-zinc-700">Gyártási év szűrő (intervallum)</label>
              <div class="relative h-10">
                <div class="absolute left-0 right-0 top-1/2 h-1 -translate-y-1/2 rounded-full bg-zinc-300"></div>
                <input :min="yearBounds.min" :max="yearBounds.max" v-model.number="selectedYearMin" type="range"
                  class="pointer-events-none absolute left-0 top-1/2 z-20 h-1 w-full -translate-y-1/2 appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-teal-600 [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:bg-teal-600"
                  @input="onMinYearInput" />
                <input :min="yearBounds.min" :max="yearBounds.max" v-model.number="selectedYearMax" type="range"
                  class="pointer-events-none absolute left-0 top-1/2 z-30 h-1 w-full -translate-y-1/2 appearance-none bg-transparent [&::-webkit-slider-thumb]:pointer-events-auto [&::-webkit-slider-thumb]:h-4 [&::-webkit-slider-thumb]:w-4 [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-orange-500 [&::-moz-range-thumb]:pointer-events-auto [&::-moz-range-thumb]:h-4 [&::-moz-range-thumb]:w-4 [&::-moz-range-thumb]:rounded-full [&::-moz-range-thumb]:border-0 [&::-moz-range-thumb]:bg-orange-500"
                  @input="onMaxYearInput" />
              </div>
              <div class="mt-2 flex items-center justify-between text-sm font-semibold text-zinc-700">
                <span>Min: {{ selectedYearMin }}</span>
                <span>Max: {{ selectedYearMax }}</span>
              </div>
            </div>
          </div>

          <div v-if="filteredModels.length === 0"
            class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center">
            <p class="text-zinc-600">Nincs egy modell sem! Hozzon létre egyet!</p>
          </div>

          <div v-else class="overflow-x-auto rounded-2xl border border-zinc-200">
            <table class="w-full border-collapse bg-white">
              <thead class="bg-zinc-50">
                <tr>
                  <th
                    class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">
                    ID</th>
                  <th
                    class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">
                    Gyártó</th>
                  <th
                    class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">
                    Modell</th>
                  <th
                    class="border-b border-zinc-200 px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-zinc-500">
                    Gyártási év</th>
                  <th
                    class="border-b border-zinc-200 px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-zinc-500">
                    Műveletek</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="model in filteredModels" :key="model.id" class="hover:bg-zinc-50">
                  <td class="border-b border-zinc-100 px-4 py-3 font-medium text-zinc-700">{{ model.id }}</td>
                  <td class="border-b border-zinc-100 px-4 py-3 font-semibold text-zinc-800">{{
                    getBrandName(model.brand_id) }}</td>
                  <td class="border-b border-zinc-100 px-4 py-3 text-zinc-800">{{ model.name }}</td>
                  <td class="border-b border-zinc-100 px-4 py-3 text-center text-zinc-700">{{ model.startyear }}-{{
                    model.endyear || 'Jelenleg' }}</td>
                  <td class="border-b border-zinc-100 px-4 py-3 text-center">
                    <div class="inline-flex gap-2">
                      <button @click="goToEdit(model.id)" class="btn-muted px-3 py-2 text-xs">
                        Szerkesztés
                      </button>
                      <button @click="handleDelete(model.id)" class="btn-accent px-3 py-2 text-xs">
                        Törlés
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </template>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-models
meta:
  title: Modellek oldal
  requiresAuth: true
  role: admin
</route>