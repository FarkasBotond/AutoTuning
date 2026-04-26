<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'

defineProps({
  showSearch: {
    type: Boolean,
    default: true
  }
})

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<template>
  <header class="w-full px-4 pt-4 md:px-6 md:pt-6">
    <div class="glass-panel w-full px-4 py-4 md:px-6">
      <div class="flex flex-wrap items-center gap-4 lg:flex-nowrap lg:gap-6">
        <div class="shrink-0">
          <RouterLink
            to="/"
            class="flex items-center justify-center rounded-2xl border border-zinc-200/80 bg-white px-3 py-2 transition hover:border-teal-200 hover:bg-teal-50"
          >
            <img :src="logo" alt="Logo" class="h-16 w-auto object-contain md:h-20" />
          </RouterLink>
        </div>

        <div v-if="showSearch" class="order-3 min-w-0 w-full flex-1 lg:order-2 lg:w-auto">
          <SearchBar />
        </div>

        <div class="order-2 flex shrink-0 items-center gap-2 md:gap-3 lg:order-3">
          <template v-if="authStore.isAuthenticated">
            <span class="hidden rounded-xl bg-zinc-100 px-3 py-2 text-xs font-semibold text-zinc-600 xl:inline">
              {{ authStore.user?.email }}
            </span>

            <RouterLink
              to="/profile"
              class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M15.75 6.75a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0" />
              </svg>
              Az én fiókom
            </RouterLink>

            <RouterLink
              to="/cart"
              class="inline-flex items-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-3 py-2 text-sm font-semibold text-orange-700 transition hover:bg-orange-100"
            >
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M2.25 3h1.386a1.5 1.5 0 0 1 1.455 1.136l.383 1.437M7.5 14.25h8.443a1.5 1.5 0 0 0 1.474-1.227l1.092-6A1.5 1.5 0 0 0 17.033 5.25H5.474M7.5 14.25 5.474 5.25M7.5 14.25l-1.125 2.25m0 0a1.125 1.125 0 1 0 2.25 0m-2.25 0h9.75m0 0a1.125 1.125 0 1 0 2.25 0">

                </path>
              </svg>
              Kosár
            </RouterLink>

            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin"
              class="inline-flex items-center rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-800"
            >
              Admin
            </RouterLink>

            <button
              @click="handleLogout"
              class="inline-flex items-center rounded-xl bg-zinc-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-zinc-700"
            >
              Kijelentkezés
            </button>
          </template>

          <template v-else>
            <RouterLink
              to="/login"
              class="inline-flex items-center rounded-xl border border-zinc-200 bg-white px-4 py-2 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-50"
            >
              Belépés
            </RouterLink>

            <RouterLink
              to="/register"
              class="inline-flex items-center rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-800"
            >
              Regisztráció
            </RouterLink>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>