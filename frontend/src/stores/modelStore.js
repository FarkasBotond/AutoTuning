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

  const parseLegacyModelLabel = (label = '') => {
    const normalized = String(label || '').trim().replace(/\s+/g, ' ')

    if (!normalized) {
      return { name: '', gen: '', mod: '' }
    }

    const match = normalized.match(/^(.*?)\s+gen\s+(.+)$/i)
    if (!match) {
      return { name: normalized, gen: '', mod: '' }
    }

    return {
      name: match[1].trim(),
      gen: match[2].trim(),
      mod: ''
    }
  }

  const pickFirstFilled = (...values) => values.find(
    value => value !== null && value !== undefined && String(value).trim() !== ''
  )

  const normalizeIncomingModel = (rawModel = {}) => {
    const legacyLabel = pickFirstFilled(rawModel.model, rawModel.name) || ''
    const legacyParsed = parseLegacyModelLabel(legacyLabel)
    const resolvedName = pickFirstFilled(rawModel.name, legacyParsed.name, rawModel.model, '-')
    const resolvedGen = pickFirstFilled(rawModel.gen, legacyParsed.gen, '-')
    const resolvedMod = pickFirstFilled(rawModel.mod, legacyParsed.mod, '-')
    const resolvedStartYear = pickFirstFilled(rawModel.startyear, rawModel.start_year)

    return {
      ...rawModel,
      name: resolvedName,
      gen: resolvedGen,
      mod: resolvedMod,
      startyear: resolvedStartYear ?? null,
      endyear: rawModel.endyear ?? rawModel.end_year ?? null,
      brand_id: rawModel.brand_id ?? rawModel.brand?.id ?? null
    }
  }

  const normalizeOutgoingModel = (formData = {}) => {
    const name = String(formData.name || '').trim()
    const gen = String(formData.gen || '-').trim()
    const mod = String(formData.mod || '-').trim()
    const startyear = formData.startyear
    const endyear = formData.endyear

    const modelLabel = `${name}${gen ? ` gen ${gen}` : ''}${mod ? ` ${mod}` : ''}`.trim()

    return {
      brand_id: formData.brand_id,
      name,
      gen,
      mod,
      startyear,
      endyear,
      model: modelLabel || name,
      start_year: startyear,
      end_year: endyear
    }
  }

  const fetchAllModels = async () => {
    isLoading.value = true
    error.value = null

    try {
      const authStore = useAuthStore()
      const response = await fetchModels(authStore.token)
      models.value = response.data.map(normalizeIncomingModel)
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
      currentModel.value = normalizeIncomingModel(response.data)
      return currentModel.value
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
      const response = await createModel(normalizeOutgoingModel(data), authStore.token)
      const normalizedModel = normalizeIncomingModel(response.data)
      models.value.push(normalizedModel)
      return normalizedModel
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
      const response = await updateModel(id, normalizeOutgoingModel(data), authStore.token)
      const normalizedModel = normalizeIncomingModel(response.data)
      const index = models.value.findIndex(m => m.id === id)
      if (index > -1) {
        models.value[index] = normalizedModel
      }
      if (currentModel.value?.id === id) {
        currentModel.value = normalizedModel
      }
      return normalizedModel
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