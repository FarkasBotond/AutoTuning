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

    const login = async (email, password) => {
      isLoading.value = true
      error.value = null

      try {
        const response = await apiLogin(email, password)
        
        if (response.data && response.data.token) {
          token.value = response.data.token
          // You can extend this to fetch user data
          user.value = { email }
          return true
        }
        return false
      } catch (err) {
        error.value = err.response?.data?.data?.message || 'Login failed'
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
