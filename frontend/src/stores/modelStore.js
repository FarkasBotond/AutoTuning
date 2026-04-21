import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchModels, fetchModel, createModel, updateModel, deleteModel } from '@/lib/api'
import { useAuthStore } from './authStore'

export const useModelStore = defineStore('model', () => {
  const models = ref([])
  const currentModel = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  const modelCount = computed(() => models.value.length)

  const fetchAllModels = async () => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await fetchModels(authStore.token)
      models.value = response.data
      return models.value
    } catch (err) {
      error.value = err.message || 'Failed to fetch models'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchSingleModel = async (id) => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await fetchModel(id, authStore.token)
      currentModel.value = response.data
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to fetch model'
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
      const response = await createModel(data, authStore.token)
      models.value.push(response.data)
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to create model'
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
      const response = await updateModel(id, data, authStore.token)
      const index = models.value.findIndex(m => m.id === id)
      if (index > -1) {
        models.value[index] = response.data
      }
      if (currentModel.value?.id === id) {
        currentModel.value = response.data
      }
      return response.data
    } catch (err) {
      error.value = err.message || 'Failed to update model'
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
      await deleteModel(id, authStore.token)
      models.value = models.value.filter(m => m.id !== id)
      if (currentModel.value?.id === id) {
        currentModel.value = null
      }
    } catch (err) {
      error.value = err.message || 'Failed to delete model'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const clearError = () => {
    error.value = null
  }

  return {
    models,
    currentModel,
    isLoading,
    error,
    modelCount,
    fetchAllModels,
    fetchSingleModel,
    create,
    update,
    destroy,
    clearError
  }
})
