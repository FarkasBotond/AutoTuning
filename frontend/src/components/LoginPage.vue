<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white px-8 py-10 shadow-lg">
      <h1 class="mb-8 text-center text-3xl font-bold text-gray-900">Login</h1>
      
      <form @submit.prevent="handleLogin" class="flex flex-col gap-6">
        <!-- Error Message -->
        <div v-if="authStore.error" class="rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
          {{ authStore.error }}
        </div>

        <!-- Email Field -->
        <div class="flex flex-col gap-2">
          <label for="email" class="text-sm font-semibold text-gray-700">Email</label>
          <input
            id="email"
            v-model="formData.email"
            type="email"
            placeholder="Enter your email"
            required
            :disabled="authStore.isLoading"
            @focus="authStore.clearError()"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <!-- Password Field -->
        <div class="flex flex-col gap-2">
          <label for="password" class="text-sm font-semibold text-gray-700">Password</label>
          <input
            id="password"
            v-model="formData.password"
            type="password"
            placeholder="Enter your password"
            required
            :disabled="authStore.isLoading"
            @focus="authStore.clearError()"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          :disabled="authStore.isLoading" 
          class="mt-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg hover:-translate-y-0.5 active:scale-95 disabled:cursor-not-allowed disabled:opacity-70"
        >
          {{ authStore.isLoading ? 'Logging in...' : 'Login' }}
        </button>
      </form>

      <!-- Register Link -->
      <div class="mt-6 text-center text-sm text-gray-600">
        Don't have an account? <router-link to="/register" class="font-semibold text-blue-600 transition-colors hover:text-blue-700">Register here</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const authStore = useAuthStore()
const router = useRouter()

const formData = ref({
  email: '',
  password: ''
})

const handleLogin = async () => {
  const { email, password } = formData.value

  if (!email || !password) {
    authStore.error = 'Please fill in all fields'
    return
  }

  const success = await authStore.login(email, password)

  if (success) {
    // Reset form
    formData.value = {
      email: '',
      password: ''
    }
    // Redirect to index page
    router.push('/')
  }
}
</script>

<style scoped>
/* All styles handled via Tailwind classes */
</style>
