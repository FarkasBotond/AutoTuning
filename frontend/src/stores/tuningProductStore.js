import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchTuningProducts } from '@/lib/api'

export const useTuningProductStore = defineStore('tuningProduct', () => {
  const products = ref([])
  const isLoading = ref(false)
  const error = ref(null)

  const productCount = computed(() => products.value.length)

  const normalizeProduct = (rawProduct) => {
    return {
      id: rawProduct.id,
      name: rawProduct.name,
      brand: rawProduct.tuning_company,
      image: rawProduct.image,
      badge: rawProduct.badge,
      oldPrice: rawProduct.old_price,
      price: rawProduct.price,
      stockText: rawProduct.is_in_stock ? 'Raktaron' : 'Rendelheto',
      isInStock: rawProduct.is_in_stock,
      carModelId: rawProduct.car_model_id,
      serviceCategoryId: rawProduct.service_category_id,
      carBrand: rawProduct.car_model?.brand?.name ?? null,
      carModel: rawProduct.car_model?.model ?? rawProduct.car_model?.name ?? null,
      category: rawProduct.service_category?.name ?? null
    }
  }

  const fetchAllProducts = async (filters = {}) => {
    isLoading.value = true
    error.value = null

    try {
      const response = await fetchTuningProducts(filters)
      products.value = response.data.map(normalizeProduct)
      return products.value
    } catch (err) {
      error.value = err.message || 'Failed to fetch tuning products'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    products,
    isLoading,
    error,
    productCount,
    fetchAllProducts
  }
})