import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import { login as apiLogin, logout as apiLogout } from '@/lib/api'

export const useAuthStore = defineStore(
  'auth',
  () => {
    const token = ref(null)
    const user = ref(null)
    const isLoading = ref(false)
    const error = ref(null)

    const isAuthenticated = computed(() => !!token.value)

    const isAdmin = computed(() => user.value?.role === 'admin')

    const login = async (email, password) => {
      isLoading.value = true
      error.value = null

      try {
        const response = await apiLogin(email, password)

        const tokenFromResponse = response?.data?.token || response?.token
        const userFromResponse = response?.data?.user || response?.user || { email }

        if (tokenFromResponse) {
          token.value = tokenFromResponse
          user.value = userFromResponse
          return true
        }

        error.value = 'Sikertelen bejelentkezés: hiányzó token a válaszból.'
        return false
      } catch (err) {
        if (err?.status === 401) {
          error.value = err?.message || 'Hibás email vagy jelszó.'
        } else {
          error.value = err?.message || err?.data?.message || 'Sikertelen bejelentkezés.'
        }
        return false
      } finally {
        isLoading.value = false
      }
    }

    const logout = () => {
      token.value = null
      user.value = null
      error.value = null
    }

    const clearError = () => {
      error.value = null
    }

    return {
      token,
      user,
      isLoading,
      error,
      isAuthenticated,
      isAdmin,
      login,
      logout,
      clearError
    }
  },
  {
    persist: {
      paths: ['token', 'user']
    }
  }
)
