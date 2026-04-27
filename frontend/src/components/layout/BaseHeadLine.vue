<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'

defineProps({
  showSearch: {
    type: Boolean,
    default: true
  },
  showDarkToggle: {
    type: Boolean,
    default: false
  }
})

const authStore = useAuthStore()
const router = useRouter()

const isDarkMode = ref(false)
const showGuestModal = ref(false)

const applyDarkMode = (enabled) => {
  isDarkMode.value = enabled
  document.documentElement.classList.toggle('dark', enabled)
  localStorage.setItem('darkMode', enabled ? 'true' : 'false')
}

const toggleDarkMode = () => {
  applyDarkMode(!isDarkMode.value)
}

onMounted(() => {
  isDarkMode.value = localStorage.getItem('darkMode') === 'true'
  document.documentElement.classList.toggle('dark', isDarkMode.value)
})

const handleLogout = () => {
  authStore.logout()
  router.push('/login')
}

const handleCartClick = () => {
  if (authStore.isAuthenticated) {
    router.push('/cart')
    return
  }

  showGuestModal.value = true
}

const goToLogin = () => {
  showGuestModal.value = false

  router.push({
    path: '/login',
    query: {
      redirect: '/cart'
    }
  })
}

const goToRegister = () => {
  showGuestModal.value = false

  router.push({
    path: '/register',
    query: {
      redirect: '/cart'
    }
  })
}

const continueAsGuest = () => {
  showGuestModal.value = false
  router.push('/cart')
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

        <button
          v-if="showDarkToggle"
          type="button"
          @click="toggleDarkMode"
          class="order-2 inline-flex shrink-0 items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50 lg:order-2"
          :title="isDarkMode ? 'Világos mód bekapcsolása' : 'Sötét mód bekapcsolása'"
        >
          <span class="text-lg leading-none">
            {{ isDarkMode ? '☀️' : '🌙' }}
          </span>
          <span class="hidden xl:inline">
            {{ isDarkMode ? 'Világos mód' : 'Sötét mód' }}
          </span>
        </button>

        <div v-if="showSearch" class="order-4 min-w-0 w-full flex-1 lg:order-3 lg:w-auto">
          <SearchBar />
        </div>

        <div class="order-3 flex shrink-0 items-center gap-2 md:gap-3 lg:order-4">
          <template v-if="authStore.isAuthenticated">
            <span class="hidden rounded-xl bg-zinc-100 px-3 py-2 text-xs font-semibold text-zinc-600 xl:inline">
              {{ authStore.user?.name || authStore.user?.email }}
            </span>

            <RouterLink
              to="/profile"
              class="inline-flex items-center gap-2 rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50"
            >
              Az én fiókom
            </RouterLink>

            <button
              type="button"
              @click="handleCartClick"
              class="inline-flex items-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-3 py-2 text-sm font-semibold text-orange-700 transition hover:bg-orange-100"
            >
              Kosár
            </button>

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
            <button
              type="button"
              @click="handleCartClick"
              class="inline-flex items-center gap-2 rounded-xl border border-orange-200 bg-orange-50 px-3 py-2 text-sm font-semibold text-orange-700 transition hover:bg-orange-100"
            >
              Kosár
            </button>

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

    <div
      v-if="showGuestModal"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4"
      @click.self="showGuestModal = false"
    >
      <div class="w-full max-w-lg rounded-3xl border border-zinc-200 bg-white p-6 shadow-2xl">
        <h2 class="text-2xl font-extrabold text-zinc-900">
          Vásárlás folytatása
        </h2>

        <p class="mt-3 text-sm font-medium text-zinc-600">
          A kosarat vendégként is használhatod, de belépéssel vagy regisztrációval később könnyebben követheted a rendeléseidet.
        </p>

        <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
          <button
            type="button"
            class="btn-primary"
            @click="goToLogin"
          >
            Belépés
          </button>

          <button
            type="button"
            class="rounded-xl border border-teal-200 bg-teal-50 px-5 py-3 text-sm font-semibold text-teal-700 transition hover:bg-teal-100"
            @click="goToRegister"
          >
            Regisztráció
          </button>
        </div>

        <button
          type="button"
          class="mt-3 w-full rounded-xl border border-zinc-200 bg-white px-5 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-100"
          @click="continueAsGuest"
        >
          Vendégként maradok
        </button>

        <button
          type="button"
          class="mt-4 w-full text-sm font-semibold text-zinc-500 transition hover:text-zinc-800"
          @click="showGuestModal = false"
        >
          Mégse
        </button>
      </div>
    </div>
  </header>
</template>