<template>
  <nav class="sticky top-0 z-100 border-b border-gray-200 bg-white shadow-sm">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-4">
        <!-- Logo/Brand -->
        <router-link to="/" class="flex items-center text-2xl font-bold text-gray-900 hover:text-blue-600 transition-colors">
          AutoTuning
        </router-link>

        <!-- Navigation Links -->
        <div class="flex items-center gap-4">
          <template v-if="authStore.isAuthenticated">
            <span class="text-sm text-gray-700">{{ authStore.user?.email }}</span>
            <router-link 
              v-if="authStore.isAdmin" 
              to="/admin" 
              class="rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-blue-700 active:scale-95"
            >
              Admin Panel
            </router-link>
            <button 
              @click="handleLogout" 
              class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-100 active:scale-95"
            >
              Logout
            </button>
          </template>
          <template v-else>
            <router-link 
              to="/login" 
              class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition-all hover:bg-gray-100 active:scale-95"
            >
              Login
            </router-link>
            <router-link 
              to="/register" 
              class="rounded-lg border border-blue-600 bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition-all hover:bg-blue-700 active:scale-95"
            >
              Register
            </router-link>
          </template>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { useAuthStore } from '@/stores/authStore'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}
</script>

<style scoped>
/* All styles handled via Tailwind classes */
</style>
