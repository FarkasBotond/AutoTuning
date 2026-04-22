<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const router = useRouter()
const authStore = useAuthStore()

const formData = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: ''
})

const isLoading = ref(false)
const error = ref(null)

const handleRegister = async () => {
  error.value = null

  if (
    !formData.value.name ||
    !formData.value.email ||
    !formData.value.password ||
    !formData.value.password_confirmation
  ) {
    error.value = 'Please fill in all fields'
    return
  }

  if (formData.value.password !== formData.value.password_confirmation) {
    error.value = 'Passwords do not match'
    return
  }

  isLoading.value = true

  try {
    const response = await fetch('http://localhost/api/registration', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
      },
      body: JSON.stringify({
        name: formData.value.name,
        email: formData.value.email,
        password: formData.value.password,
        password_confirmation: formData.value.password_confirmation
      })
    })

    const data = await response.json()

    if (response.ok) {
      // Auto-login after registration
      const loginSuccess = await authStore.login(
        formData.value.email,
        formData.value.password
      )

      if (loginSuccess) {
        router.push('/landing')
      }
    } else {
      // Handle validation errors - Laravel returns errors at root level
      error.value = data.message || data.data?.message || 'Registration failed'
      
      // If there are field-specific errors, show the first one
      if (data.errors) {
        const firstError = Object.values(data.errors)[0]
        if (Array.isArray(firstError) && firstError[0]) {
          error.value = firstError[0]
        }
      }
    }
  } catch (err) {
    error.value = 'An error occurred during registration'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white px-8 py-10 shadow-lg">
      <h1 class="mb-8 text-center text-3xl font-bold text-gray-900">Create Account</h1>

      <form @submit.prevent="handleRegister" class="flex flex-col gap-6">
        <!-- Error Message -->
        <div v-if="error" class="rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
          {{ error }}
        </div>

        <!-- Name Field -->
        <div class="flex flex-col gap-2">
          <label for="name" class="text-sm font-semibold text-gray-700">Full Name</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Enter your full name"
            required
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
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
            :disabled="isLoading"
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
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <!-- Confirm Password Field -->
        <div class="flex flex-col gap-2">
          <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Confirm Password</label>
          <input
            id="password_confirmation"
            v-model="formData.password_confirmation"
            type="password"
            placeholder="Confirm your password"
            required
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <!-- Submit Button -->
        <button 
          type="submit" 
          :disabled="isLoading" 
          class="mt-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg hover:-translate-y-0.5 active:scale-95 disabled:cursor-not-allowed disabled:opacity-70"
        >
          {{ isLoading ? 'Creating Account...' : 'Register' }}
        </button>
      </form>

      <!-- Login Link -->
      <div class="mt-6 text-center text-sm text-gray-600">
        Already have an account? <router-link to="/login" class="font-semibold text-blue-600 transition-colors hover:text-blue-700">Login here</router-link>
      </div>
    </div>
  </div>
</template>
          {{ isLoading ? 'Creating Account...' : 'Register' }}
        </button>
      </form>

      <!-- Login Link -->
      <div class="login-link">
        Already have an account? <router-link to="/login">Login here</router-link>
      </div>
    </div>
  </div>
</template>

<route lang="yaml">
name: register
meta:
  title: Register
</route>

<style scoped>
/* Styles are handled via Tailwind classes */
</style>
