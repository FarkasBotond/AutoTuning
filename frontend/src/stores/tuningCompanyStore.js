import { defineStore } from 'pinia'
import http from '@/utils/http.mjs'

export const useTuningCompanyStore = defineStore('tuningCompanyStore', {
    state: () => ({
        companies: [],
        selectedCompany: null,
        isLoading: false,
        error: null
    }),

    actions: {
        async fetchAllCompanies() {
            this.isLoading = true
            this.error = null

            try {
                const response = await http.get('/tuning-companies')
                this.companies = response.data.data ?? response.data
            } catch (error) {
                this.error = error.response?.data?.message ?? 'Nem sikerult betolteni a tuning cegeket.'
            } finally {
                this.isLoading = false
            }
        },

        async fetchCompanyById(id) {
            this.isLoading = true
            this.error = null
            this.selectedCompany = null

            try {
                const response = await http.get(`/tuning-companies/${id}`)
                this.selectedCompany = response.data.data ?? response.data
            } catch (error) {
                this.error = error.response?.data?.message ?? 'Nem sikerult betolteni a tuning ceget.'
            } finally {
                this.isLoading = false
            }
        }
    }
})