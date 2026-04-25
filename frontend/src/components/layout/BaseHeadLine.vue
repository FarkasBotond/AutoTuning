<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <header class="w-full bg-zinc-300/80 px-4 py-4 backdrop-blur-sm">
    <div class="w-full border border-zinc-800 bg-white/90 px-5 py-4 shadow-lg">
      <div class="flex items-center gap-5">
        <div class="shrink-0">
          <RouterLink :to="{ name: 'landing' }"
            class="flex items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-100 p-2 transition hover:bg-zinc-200">
            <img :src="logo" alt="Logo" class="h-28 w-auto object-contain" />
          </RouterLink>
        </div>

        <div class="min-w-0 flex-1">
          <SearchBar />
        </div>

        <div class="flex shrink-0 items-center gap-3">
          <template v-if="authStore.isAuthenticated">
            <span class="hidden text-sm font-semibold text-zinc-500 xl:inline">
              {{ authStore.user?.email }}
            </span>

            <RouterLink to="/profile"
              class="inline-flex items-center rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-2 text-xl font-semibold text-zinc-800 transition hover:bg-zinc-200">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0" />
              </svg>
              Az én fiókom
            </RouterLink>

            <RouterLink to="/cart"
              class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-xl font-semibold text-red-600 transition hover:bg-red-100">
              <svg xlmns="https://wwww.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386a1.5 1.5 0 0 1 1.455 1.136l.383 1.437M7.5 14.25h8.443a1.5 1.5 0 0 0 1.474-1.227l1.092-6A1.5 1.5 0 0 0 17.033 5.25H5.474M7.5 14.25 5.474 5.25M7.5 14.25l-1.125 2.25m0 0a1.125 1.125 0 1 0 2.25 0m-2.25 0h9.75m0 0a1.125 1.125 0 1 0 2.25 0">

                </path>
              </svg>
              Kosár
            </RouterLink>

            <RouterLink v-if="authStore.isAdmin" to="/admin"
              class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              Admin
            </RouterLink>

            <button @click="handleLogout"
              class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-xl font-semibold text-white transition hover:bg-red-700">
              Kijelentkezés
            </button>
          </template>

          <template v-else>
            <RouterLink to="/cart"
              class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100">
              Kosár
            </RouterLink>

            <RouterLink to="/login"
              class="inline-flex items-center rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-200">
              Belépés
            </RouterLink>

            <RouterLink to="/register"
              class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">
              Regisztráció
            </RouterLink>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>