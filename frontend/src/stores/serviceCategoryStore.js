import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchServiceCategories } from '@/lib/api'

export const useServiceCategoryStore = defineStore('serviceCategory', () => {
  const categories = ref([])
  const isLoading = ref(false)
  const error = ref(null)

  const categoryCount = computed(() => categories.value.length)

  const normalizeCategory = (rawCategory) => {
    return {
      id: rawCategory.id,
      name: rawCategory.name
    }
  }

  const fetchAllCategories = async () => {
    isLoading.value = true
    error.value = null

    try {
      const response = await fetchServiceCategories()
      categories.value = response.data.map(normalizeCategory)

      return categories.value
    } catch (err) {
      error.value = err.message || 'Nem sikerült betölteni a kategóriákat.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    categories,
    isLoading,
    error,
    categoryCount,
    fetchAllCategories
  }
})