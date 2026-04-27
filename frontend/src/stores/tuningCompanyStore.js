import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { fetchTuningCompanies, fetchTuningCompany } from '@/lib/api'

export const useTuningCompanyStore = defineStore('tuningCompany', () => {
  const companies = ref([])
  const selectedCompany = ref(null)
  const isLoading = ref(false)
  const error = ref(null)

  const companyCount = computed(() => companies.value.length)

  const normalizeCompany = (rawCompany) => {
    return {
      id: rawCompany.id,
      name: rawCompany.name,
      description: rawCompany.description,
      websiteUrl: rawCompany.website_url
    }
  }

  const fetchAllCompanies = async () => {
    isLoading.value = true
    error.value = null

    try {
      const response = await fetchTuningCompanies()
      companies.value = response.data.map(normalizeCompany)

      return companies.value
    } catch (err) {
      error.value = err.message || 'Nem sikerült betölteni a tuning cégeket.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  const fetchCompanyById = async (id) => {
    isLoading.value = true
    error.value = null
    selectedCompany.value = null

    try {
      const response = await fetchTuningCompany(id)
      selectedCompany.value = normalizeCompany(response.data)

      return selectedCompany.value
    } catch (err) {
      error.value = err.message || 'Nem sikerült betölteni a tuning céget.'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  return {
    companies,
    selectedCompany,
    isLoading,
    error,
    companyCount,
    fetchAllCompanies,
    fetchCompanyById
  }
})