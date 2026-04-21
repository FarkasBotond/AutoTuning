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
    console.error('Failed to load data:', error)
  }
})

const handleDelete = async (modelId) => {
  if (confirm('Are you sure you want to delete this model?')) {
    try {
      await modelStore.destroy(modelId)
    } catch (error) {
      console.error('Failed to delete model:', error)
    }
  }
}

const getBrandName = (brandId) => {
  const brand = brandStore.brands.find(b => b.id === brandId)
  return brand?.name || 'Unknown'
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
    <div class="container mx-auto p-6">
      <div class="admin-nav mb-6">
        <button
          @click="() => router.push('/admin')"
          class="nav-back-btn"
        >
          ← Admin Panel
        </button>
        <div class="admin-tabs">
          <button
            @click="() => router.push('/admin/brands')"
            class="admin-tab"
          >
            Brands
          </button>
          <button class="admin-tab active">Models</button>
        </div>
      </div>
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-4xl font-bold">Car Models Management</h1>
        <button
          @click="goToCreate"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded"
        >
          Add New Model
        </button>
      </div>

      <div v-if="modelStore.isLoading || brandStore.isLoading" class="text-center py-8">
        <p class="text-gray-500">Loading models...</p>
      </div>

      <div v-else-if="modelStore.error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ modelStore.error }}
      </div>

      <div v-else-if="modelStore.models.length === 0" class="text-center py-8">
        <p class="text-gray-500">No models found. Create one to get started!</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
          <thead class="bg-gray-200">
            <tr>
              <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Brand</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Name</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Gen</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Mod</th>
              <th class="border border-gray-300 px-4 py-2 text-center">Years</th>
              <th class="border border-gray-300 px-4 py-2 text-center">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="model in modelStore.models" :key="model.id" class="hover:bg-gray-100">
              <td class="border border-gray-300 px-4 py-2">{{ model.id }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ getBrandName(model.brand_id) }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ model.name }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ model.gen }}</td>
              <td class="border border-gray-300 px-4 py-2">{{ model.mod }}</td>
              <td class="border border-gray-300 px-4 py-2 text-center">{{ model.startyear }}-{{ model.endyear || 'Present' }}</td>
              <td class="border border-gray-300 px-4 py-2 text-center">
                <button
                  @click="goToEdit(model.id)"
                  class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-1 px-3 rounded mr-2"
                >
                  Edit
                </button>
                <button
                  @click="handleDelete(model.id)"
                  class="bg-red-500 hover:bg-red-600 text-white font-bold py-1 px-3 rounded"
                >
                  Delete
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </BaseLayout>
</template>

<style scoped>
.admin-nav {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  padding-bottom: 1rem;
  border-bottom: 2px solid #e5e7eb;
}

.nav-back-btn {
  padding: 0.5rem 1rem;
  background: #f3f4f6;
  border: 1px solid #d1d5db;
  border-radius: 6px;
  font-weight: 600;
  color: #1f2937;
  cursor: pointer;
  transition: all 0.2s ease;
}

.nav-back-btn:hover {
  background: #e5e7eb;
  border-color: #9ca3af;
}

.admin-tabs {
  display: flex;
  gap: 1rem;
  margin-left: auto;
}

.admin-tab {
  padding: 0.5rem 1rem;
  background: transparent;
  border: 2px solid #d1d5db;
  border-radius: 6px;
  font-weight: 600;
  color: #6b7280;
  cursor: pointer;
  transition: all 0.2s ease;
}

.admin-tab:hover {
  border-color: #9ca3af;
  color: #1f2937;
}

.admin-tab.active {
  background: #3b82f6;
  border-color: #3b82f6;
  color: white;
}
</style>

<route lang="yaml">
name: admin-models
meta:
  title: Model Management
  requiresAuth: true
  role: admin
</route>
