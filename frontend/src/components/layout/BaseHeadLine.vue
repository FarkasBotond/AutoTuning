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
   <div class="w-full border border-zinc-300 bg-white/90 px-5 py-4 shadow-lg">
      <div class="flex items-center gap-5">
        <div class="shrink-0">
          <RouterLink
            :to="{ name: 'landing' }"
            class="flex items-center justify-center rounded-2xl border border-zinc-200 bg-zinc-100 p-2 transition hover:bg-zinc-200"
          >
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

            <RouterLink
              to="/profile"
              class="inline-flex items-center rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-200"
            >
              Az én fiókom
            </RouterLink>

            <RouterLink
              to="/cart"
              class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100"
            >
              Kosár
            </RouterLink>

            <RouterLink
              v-if="authStore.isAdmin"
              to="/admin"
              class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
              Admin
            </RouterLink>

            <button
              @click="handleLogout"
              class="inline-flex items-center rounded-xl bg-red-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-700"
            >
              Kijelentkezés
            </button>
          </template>

          <template v-else>
            <RouterLink
              to="/cart"
              class="inline-flex items-center rounded-xl border border-red-200 bg-red-50 px-4 py-2 text-sm font-semibold text-red-600 transition hover:bg-red-100"
            >
              Kosár
            </RouterLink>

            <RouterLink
              to="/login"
              class="inline-flex items-center rounded-xl border border-zinc-300 bg-zinc-100 px-4 py-2 text-sm font-semibold text-zinc-800 transition hover:bg-zinc-200"
            >
              Belépés
            </RouterLink>

            <RouterLink
              to="/register"
              class="inline-flex items-center rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
              Regisztráció
            </RouterLink>
          </template>
        </div>
      </div>
    </div>
  </header>
</template>