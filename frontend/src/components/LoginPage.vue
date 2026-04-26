<template>
  <div class="flex min-h-screen items-center justify-center px-4 py-10 md:px-6">
    <div class="glass-panel grid w-full max-w-5xl overflow-hidden lg:grid-cols-[1.1fr_1fr]">
      <aside class="hidden bg-gradient-to-br from-teal-700 via-teal-800 to-zinc-900 p-10 text-white lg:flex lg:flex-col lg:justify-between">
        <div>
          <p class="text-xs font-bold uppercase tracking-[0.24em] text-teal-100">RaceDistrict</p>
          <h2 class="mt-5 text-4xl font-extrabold leading-tight">Üdv újra a
            <span class="text-orange-300">garázsban</span>
          </h2>
          <p class="mt-4 max-w-sm text-sm text-teal-100/90">
            Jelentkezz be, hogy elérd a profilod, rendeléseid és a személyre szabott ajánlatokat.
          </p>
        </div>
        <p class="text-xs text-teal-100/70">Gyors, biztonságos, modern vásárlói élmény.</p>
      </aside>

      <section class="p-6 sm:p-8 lg:p-10">
        <h1 class="text-3xl font-extrabold tracking-tight text-zinc-900">Bejelentkezés</h1>
        <p class="mt-2 text-sm text-zinc-500">Lépj be a fiókodba néhány másodperc alatt.</p>
        
        <form @submit.prevent="handleLogin" class="mt-8 flex flex-col gap-5">
          <div v-if="authStore.error" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
            {{ authStore.error }}
          </div>

          <div class="flex flex-col gap-2">
            <label for="email" class="text-sm font-semibold text-zinc-700">Email</label>
            <input
              id="email"
              v-model="formData.email"
              type="email"
              placeholder="pelda@email.com"
              required
              :disabled="authStore.isLoading"
              @focus="authStore.clearError()"
              class="brand-input"
            />
          </div>

          <div class="flex flex-col gap-2">
            <label for="password" class="text-sm font-semibold text-zinc-700">Jelszó</label>
            <input
              id="password"
              v-model="formData.password"
              type="password"
              placeholder="••••••••"
              required
              :disabled="authStore.isLoading"
              @focus="authStore.clearError()"
              class="brand-input"
            />
          </div>

          <button 
            type="submit" 
            :disabled="authStore.isLoading" 
            class="btn-primary mt-2 w-full disabled:cursor-not-allowed disabled:opacity-70"
          >
            {{ authStore.isLoading ? 'Bejelentkezés...' : 'Belépés' }}
          </button>
        </form>

        <div class="mt-6 text-center text-sm text-zinc-600">
          Nincs még fiókod?
          <router-link to="/register" class="font-semibold text-teal-700 transition-colors hover:text-teal-800">Regisztrálj itt</router-link>
        </div>
      </section>
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
</style>
