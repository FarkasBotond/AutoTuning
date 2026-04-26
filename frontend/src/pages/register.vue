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
  <div class="flex min-h-screen items-center justify-center px-4 py-10 md:px-6">
    <div class="glass-panel grid w-full max-w-5xl overflow-hidden lg:grid-cols-[1.1fr_1fr]">
      <aside class="hidden bg-gradient-to-br from-zinc-900 via-zinc-800 to-teal-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-100">RaceDistrict</p>
          <h2 class="mt-5 text-4xl font-extrabold leading-tight">Készíts fiókot és
            <span class="text-orange-300">indulhat a tuning</span>
          </h2>
          <p class="mt-4 max-w-sm text-sm text-zinc-200">
            Mentett adatok, gyors rendelés, nyomon követhető státusz és személyre szabott ajánlatok.
          </p>
        </div>
        <p class="text-xs text-zinc-300">Regisztráció után azonnal beléptetünk.</p>
      </aside>

      <section class="p-6 sm:p-8 lg:p-10">
        <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900">Fiók létrehozása</h1>
        <p class="mt-2 text-sm text-zinc-500">Töltsd ki az adatokat, és már használhatod is a felületet.</p>

        <form @submit.prevent="handleRegister" class="mt-8 flex flex-col gap-5">
          <div v-if="error" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ error }}
          </div>

          <div class="flex flex-col gap-2">
            <label for="name" class="text-sm font-semibold text-zinc-700">Teljes név</label>
            <input
              id="name"
              v-model="formData.name"
              type="text"
              placeholder="Teljes neved"
              required
              :disabled="isLoading"
              class="brand-input"
            />
          </div>

          <div class="flex flex-col gap-2">
            <label for="email" class="text-sm font-semibold text-zinc-700">Email</label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              placeholder="pelda@email.com"
              required
              :disabled="isLoading"
              class="brand-input"
            />
          </div>

          <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div class="flex flex-col gap-2">
              <label for="password" class="text-sm font-semibold text-zinc-700">Jelszó</label>
              <input
                id="password"
                v-model="formData.password"
                type="password"
                placeholder="••••••••"
                required
                :disabled="isLoading"
                class="brand-input"
              />
            </div>

            <div class="flex flex-col gap-2">
              <label for="password_confirmation" class="text-sm font-semibold text-zinc-700">Jelszó újra</label>
              <input
                id="password_confirmation"
                v-model="formData.password_confirmation"
                type="password"
                placeholder="••••••••"
                required
                :disabled="isLoading"
                class="brand-input"
              />
            </div>
          </div>

          <button 
            type="submit" 
            :disabled="isLoading" 
            class="btn-primary mt-2 w-full disabled:cursor-not-allowed disabled:opacity-70"
          >
            {{ isLoading ? 'Fiók létrehozása...' : 'Regisztráció' }}
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-zinc-600">
          Van már fiókod?
          <router-link to="/login" class="font-semibold text-teal-700 transition-colors hover:text-teal-800">Bejelentkezés itt</router-link>
        </div>
      </section>
    </div>
  </div>
</template>

<route lang="yaml">
name: register
meta:
  title: Regisztráció
</route>
