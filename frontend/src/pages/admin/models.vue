<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useModelStore } from '@stores/modelStore'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'

const router = useRouter()
const modelStore = useModelStore()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const isAdmin = computed(() => authStore.user?.role === 'admin')

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    await Promise.all([
      modelStore.fetchAllModels(),
      brandStore.fetchAllBrands()
    ])
  } catch (error) {
    console.error('Nem sikerült adatot betölteni:', error)
  }
})

const handleDelete = async (modelId) => {
  if (confirm('Biztosan szeretné törölni ezt a modellt?')) {
    try {
      await modelStore.destroy(modelId)
    } catch (error) {
      console.error('A törlés nem sikerült:', error)
    }
  }
}

const getBrandName = (brandId) => {
  const brand = brandStore.brands.find(b => b.id === brandId)
  return brand?.name || 'Ismeretlen'
}

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
        <button
          @click="() => router.push('/admin')"
          class="btn-muted px-4 py-2"
        >
          ← Admin Panel
        </button>
        <div class="ml-auto flex items-center gap-2">
          <button
            @click="() => router.push('/admin/brands')"
            class="btn-muted px-4 py-2 text-xs"
          >
            Gyártók
          </button>
          <button class="btn-primary px-4 py-2 text-xs">Modellek</button>
        </div>
      </div>

      <div class="glass-panel p-6 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
          <h1 class="text-3xl font-extrabold text-zinc-900">Modellek kezelése</h1>
          <button
            @click="goToCreate"
            class="btn-primary"
          >
            Új modell hozzáadása
          </button>
        </div>

        <div v-if="modelStore.isLoading || brandStore.isLoading" class="text-center py-8">
          <p class="text-zinc-500">Betöltés...</p>
        </div>

        <div v-else-if="modelStore.error" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
          {{ modelStore.error }}
        </div>

        <div v-else-if="modelStore.models.length === 0" class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center">
          <p class="text-zinc-600">Nincs egy modell sem! Hozzon létre egyet!</p>
        </div>

        <div v-else class="overflow-x-auto rounded-2xl border border-zinc-200">
          <table class="w-full border-collapse bg-white">
            <thead class="bg-zinc-50">
              <tr>
                <th class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">ID</th>
                <th class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">Gyártó</th>
                <th class="border-b border-zinc-200 px-4 py-3 text-left text-xs font-bold uppercase tracking-wider text-zinc-500">Modell</th>
                <th class="border-b border-zinc-200 px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-zinc-500">Gyártási év</th>
                <th class="border-b border-zinc-200 px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-zinc-500">Műveletek</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="model in modelStore.models" :key="model.id" class="hover:bg-zinc-50">
                <td class="border-b border-zinc-100 px-4 py-3 font-medium text-zinc-700">{{ model.id }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 font-semibold text-zinc-800">{{ getBrandName(model.brand_id) }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 text-zinc-800">{{ model.name }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 text-center text-zinc-700">{{ model.startyear }}-{{ model.endyear || 'Jelenleg' }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 text-center">
                  <div class="inline-flex gap-2">
                    <button
                      @click="goToEdit(model.id)"
                      class="btn-muted px-3 py-2 text-xs"
                    >
                      Szerkesztés
                    </button>
                    <button
                      @click="handleDelete(model.id)"
                      class="btn-accent px-3 py-2 text-xs"
                    >
                      Törlés
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-models
meta:
  title: Model Management
  requiresAuth: true
  role: admin
</route>