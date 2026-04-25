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
  <header class="w-full bg-white border-b border-gray-200">
    <div class="flex w-full items-center gap-4 px-8 py-3">
      <div class="shrink-0">
        <RouterLink :to="{ name: 'landing' }">
          <img :src="logo" alt="Logo" class="h-40 w-auto object-contain" />
        </RouterLink>
      </div>

      <div class="min-w-0 flex-1">
        <SearchBar />
      </div>

      <div class="flex shrink-0 items-center gap-3">
        <template v-if="authStore.isAuthenticated">
          <span class="text-sm font-semibold text-gray-700">{{ authStore.user?.email }}</span>
          
          <RouterLink to="/profile"
            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition">
            Az én fiókom
          </RouterLink>

          <RouterLink to="/cart"
            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition">
            Kosár
          </RouterLink>

          <RouterLink 
            v-if="authStore.isAdmin"
            to="/admin" 
            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
            Admin
          </RouterLink>

          <button 
            @click="handleLogout" 
            class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white hover:bg-red-700 transition">
            Kijelentkezés
          </button>
        </template>
        <template v-else>
          <RouterLink to="/cart"
            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition">
            Cart
          </RouterLink>

          <RouterLink to="/login"
            class="inline-flex items-center rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200 transition">
            Login
          </RouterLink>
          
          <RouterLink to="/register"
            class="inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 transition">
            Register
          </RouterLink>
        </template>
      </div>
    </div>
  </header>
</template>