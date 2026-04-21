import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchBrands, fetchBrand, createBrand, updateBrand, deleteBrand } from '@/lib/api'
import { useAuthStore } from './authStore'

export const useBrandStore = defineStore('brand', () => {
  const brands = ref([])
  const currentBrand = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  const brandCount = computed(() => brands.value.length)

  const fetchAllBrands = async () => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await fetchBrands(authStore.token)
      brands.value = response.data
      return brands.value
    } catch (err) {
      error.value = err.message || 'Failed to fetch brands'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchSingleBrand = async (id) => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await fetchBrand(id, authStore.token)
      currentBrand.value = response.data
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to fetch brand'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const create = async (data) => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await createBrand(data, authStore.token)
      brands.value.push(response.data)
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to create brand'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const update = async (id, data) => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await updateBrand(id, data, authStore.token)
      const index = brands.value.findIndex(b => b.id === id)
      if (index > -1) {
        brands.value[index] = response.data
      }
      if (currentBrand.value?.id === id) {
        currentBrand.value = response.data
      }
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to update brand'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const destroy = async (id) => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      await deleteBrand(id, authStore.token)
      brands.value = brands.value.filter(b => b.id !== id)
      if (currentBrand.value?.id === id) {
        currentBrand.value = null
      }
    } catch (err) {
      error.value = err.message || 'Failed to delete brand'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    brands,
    currentBrand,
    isLoading,
    error,
    brandCount,
    fetchAllBrands,
    fetchSingleBrand,
    create,
    update,
    destroy,
    clearError
  }
})
