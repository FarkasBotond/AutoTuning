<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'

const router = useRouter()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const isAdmin = computed(() => authStore.user?.role === 'admin')

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    await brandStore.fetchAllBrands()
  } catch (error) {
    console.error('Sikertelen gyártó betöltés:', error)
  }
})

const handleDelete = async (brandId) => {
  if (confirm('Biztosan törölni szeretné ezt a gyártót? A gyártó összes modellje törölve lesz ezzel!')) {
    try {
      await brandStore.destroy(brandId)
    } catch (error) {
      console.error('A gyártót nem sikerült törölni:', error)
    }
  }
}

const goToCreate = () => {
  router.push('/admin/brands/new')
}

const goToEdit = (brandId) => {
  router.push(`/admin/brands/${brandId}`)
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
          <button class="btn-primary px-4 py-2 text-xs">Gyártók</button>
          <button @click="() => router.push('/admin/models')" class="btn-muted px-4 py-2 text-xs">
            Modellek
          </button>
        </div>
      </div>

      <div class="glass-panel p-6 md:p-8">
        <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
          <h1 class="text-3xl font-extrabold text-zinc-900">Gyártók kezelése</h1>
          <button @click="goToCreate" class="btn-primary">
            Új gyártó
          </button>
        </div>

        <div v-if="brandStore.isLoading" class="text-center py-8">
          <p class="text-zinc-500">Betöltés...</p>
        </div>

        <div v-else-if="brandStore.error"
          class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
          {{ brandStore.error }}
        </div>

        <div v-else-if="brandStore.brands.length === 0"
          class="rounded-2xl border border-dashed border-zinc-300 bg-zinc-50 p-8 text-center">
          <p class="text-zinc-600">Nincs egy gyártó sem! Hozz létre egyet.</p>
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
                  Név</th>
                <th
                  class="border-b border-zinc-200 px-4 py-3 text-center text-xs font-bold uppercase tracking-wider text-zinc-500">
                  Műveletek</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="brand in brandStore.brands" :key="brand.id" class="hover:bg-zinc-50">
                <td class="border-b border-zinc-100 px-4 py-3 font-medium text-zinc-700">{{ brand.id }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 font-semibold text-zinc-900">{{ brand.name }}</td>
                <td class="border-b border-zinc-100 px-4 py-3 text-center">
                  <div class="inline-flex gap-2">
                    <button @click="goToEdit(brand.id)" class="btn-muted px-3 py-2 text-xs">
                      Szerkesztés
                    </button>
                    <button @click="handleDelete(brand.id)" class="btn-accent px-3 py-2 text-xs">
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
name: admin-brands
meta:
  title: Gyártók oldal
  requiresAuth: true
  role: admin
</route>
