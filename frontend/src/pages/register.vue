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
    error.value = 'Kérjük az összes mezőt töltse ki!'
    return
  }

  if (formData.value.password !== formData.value.password_confirmation) {
    error.value = 'A jelszavak nem egyeznek'
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

      const loginSuccess = await authStore.login(
        formData.value.email,
        formData.value.password
      )

      if (loginSuccess) {
        router.push('/')
      }
    } else {
      error.value = data.message || data.data?.message || 'Nem sikerült a regisztráció!'
      
      if (data.errors) {
        const firstError = Object.values(data.errors)[0]
        if (Array.isArray(firstError) && firstError[0]) {
          error.value = firstError[0]
        }
      }
    }
  } catch (err) {
    error.value = 'Nem sikerült a regisztráció!'
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="flex min-h-screen items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 px-4 py-12">
    <div class="w-full max-w-md rounded-2xl bg-white px-8 py-10 shadow-lg">
      <h1 class="mb-8 text-center text-3xl font-bold text-gray-900">Fiók létrehozása</h1>

      <form @submit.prevent="handleRegister" class="flex flex-col gap-6">
        <div v-if="error" class="rounded-lg border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-700">
          {{ error }}
        </div>

        <div class="flex flex-col gap-2">
          <label for="name" class="text-sm font-semibold text-gray-700">Teljes név</label>
          <input
            id="name"
            v-model="formData.name"
            type="text"
            placeholder="Teljes neve"
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
            placeholder="Email címe"
            required
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="password" class="text-sm font-semibold text-gray-700">Jelszó</label>
          <input
            id="password"
            v-model="formData.password"
            type="password"
            placeholder="Jelszava"
            required
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <div class="flex flex-col gap-2">
          <label for="password_confirmation" class="text-sm font-semibold text-gray-700">Jelszó újra</label>
          <input
            id="password_confirmation"
            v-model="formData.password_confirmation"
            type="password"
            placeholder="Jelszava újra"
            required
            :disabled="isLoading"
            class="rounded-lg border-2 border-gray-300 px-4 py-3 text-gray-900 transition-colors placeholder-gray-400 focus:border-blue-500 focus:outline-none disabled:cursor-not-allowed disabled:bg-gray-100"
          />
        </div>

        <button 
          type="submit" 
          :disabled="isLoading" 
          class="mt-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-3 font-semibold text-white transition-all hover:shadow-lg hover:-translate-y-0.5 active:scale-95 disabled:cursor-not-allowed disabled:opacity-70"
        >
          {{ isLoading ? 'Fiók létrehozása...' : 'Regisztráció' }}
        </button>
      </form>

      <div class="mt-6 text-center text-sm text-gray-600">
        Van már fiókja? <router-link to="/login" class="font-semibold text-blue-600 transition-colors hover:text-blue-700">Bejelentkezés itt</router-link>
      </div>
    </div>
  </div>
</template>

<route lang="yaml">
name: register
meta:
  title: Regisztráció
</route>
