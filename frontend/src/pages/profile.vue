<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { updateProfileEmail, updateProfilePassword } from '@/lib/api'
import BaseFooter from '@/components/BaseFooter.vue'

const authStore = useAuthStore()
const router = useRouter()

const emailForm = ref({
  email: '',
  current_password: ''
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

const isUpdatingEmail = ref(false)
const isUpdatingPassword = ref(false)
const emailSuccess = ref('')
const emailError = ref('')
const passwordSuccess = ref('')
const passwordError = ref('')

const goBack = () => {
  if (window.history.length > 1) {
    router.back()
    return
  }

  router.push('/')
}

onMounted(async () => {
  emailForm.value.email = authStore.user?.email || ''
})

const userName = computed(() => {
  return authStore.user?.name || authStore.user?.email?.split('@')[0] || 'Felhasználó'
})

const userEmail = computed(() => {
  return authStore.user?.email || 'email@example.com'
})

const createdAt = computed(() => {
  if (authStore.user?.created_at) {
    return new Date(authStore.user.created_at).toLocaleDateString('hu-HU', {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    })
  }
  return '2026. április 1.'
})

const userOrders = ref([])

const formatPrice = (price) => {
  return new Intl.NumberFormat('hu-HU').format(price)
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('hu-HU', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusColor = (status) => {
  switch (status) {
    case 'completed':
      return 'bg-green-100 text-green-800'
    case 'processing':
      return 'bg-blue-100 text-blue-800'
    case 'pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'cancelled':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getStatusLabel = (status) => {
  switch (status) {
    case 'completed':
      return 'Kiszállítva'
    case 'processing':
      return 'Feldolgozás alatt'
    case 'pending':
      return 'Függőben'
    case 'cancelled':
      return 'Törölve'
    default:
      return status
  }
}

const handleEmailUpdate = async () => {
  emailSuccess.value = ''
  emailError.value = ''

  if (!emailForm.value.email || !emailForm.value.current_password) {
    emailError.value = 'Add meg az új email címet és a jelenlegi jelszót.'
    return
  }

  isUpdatingEmail.value = true

  try {
    const response = await updateProfileEmail(emailForm.value, authStore.token)
    const updatedUser = response?.data?.user

    if (updatedUser) {
      authStore.user = {
        ...authStore.user,
        ...updatedUser
      }
    }

    emailForm.value.current_password = ''
    emailSuccess.value = response?.data?.message || 'Email cím sikeresen frissítve.'
  } catch (err) {
    emailError.value = err?.message || 'Nem sikerült az email cím módosítása.'
  } finally {
    isUpdatingEmail.value = false
  }
}

const handlePasswordUpdate = async () => {
  passwordSuccess.value = ''
  passwordError.value = ''

  if (
    !passwordForm.value.current_password ||
    !passwordForm.value.password ||
    !passwordForm.value.password_confirmation
  ) {
    passwordError.value = 'Tölts ki minden jelszó mezőt.'
    return
  }

  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    passwordError.value = 'Az új jelszavak nem egyeznek.'
    return
  }

  isUpdatingPassword.value = true

  try {
    const response = await updateProfilePassword(passwordForm.value, authStore.token)
    passwordForm.value = {
      current_password: '',
      password: '',
      password_confirmation: ''
    }
    passwordSuccess.value = response?.data?.message || 'Jelszó sikeresen frissítve.'
  } catch (err) {
    passwordError.value = err?.message || 'Nem sikerült a jelszó módosítása.'
  } finally {
    isUpdatingPassword.value = false
  }
}
</script>

<template>
  <div class="min-h-screen">
    <main class="mx-auto w-full max-w-[1100px] px-4 py-4 md:px-6">
      <button
        type="button"
        class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900"
        @click="goBack"
      >
        ← Vissza
      </button>

      <section>
        <div class="glass-panel mb-6 p-8">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h1 class="text-4xl font-bold text-zinc-900">{{ userName }}</h1>
              <p class="mt-2 text-lg text-zinc-600">{{ userEmail }}</p>
            </div>
            <div class="text-right">
              <div class="inline-block rounded-full bg-gradient-to-br from-teal-600 to-cyan-700 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-white">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Fiók létrehozva</p>
              <p class="text-lg font-bold text-zinc-900">{{ createdAt }}</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Összes rendelés</p>
              <p class="text-lg font-bold text-zinc-900">{{ userOrders.length }} db</p>
            </div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Teljes költés</p>
              <p class="text-lg font-bold text-teal-700">{{ formatPrice(userOrders.reduce((sum, order) => sum + order.total, 0)) }} Ft</p>
            </div>
          </div>
        </div>

        <div class="glass-panel mb-6 p-8">
          <h2 class="mb-6 text-2xl font-bold text-zinc-900">Profil beállítások</h2>

          <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
              <h3 class="text-xl font-bold text-zinc-900">Email cím módosítása</h3>
              <p class="mt-1 text-sm text-zinc-600">Az email cím módosításához add meg a jelenlegi jelszavad.</p>

              <form class="mt-5 space-y-4" @submit.prevent="handleEmailUpdate">
                <div class="space-y-2">
                  <label class="text-sm font-semibold text-zinc-700" for="new-email">Új email cím</label>
                  <input id="new-email" v-model="emailForm.email" type="email" class="brand-input" :disabled="isUpdatingEmail" required />
                </div>

                <div class="space-y-2">
                  <label class="text-sm font-semibold text-zinc-700" for="email-current-password">Jelenlegi jelszó</label>
                  <input id="email-current-password" v-model="emailForm.current_password" type="password" class="brand-input" :disabled="isUpdatingEmail" required />
                </div>

                <p v-if="emailError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ emailError }}</p>
                <p v-if="emailSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ emailSuccess }}</p>

                <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingEmail">
                  {{ isUpdatingEmail ? 'Mentés...' : 'Email frissítése' }}
                </button>
              </form>
            </div>

            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
              <h3 class="text-xl font-bold text-zinc-900">Jelszó módosítása</h3>
              <p class="mt-1 text-sm text-zinc-600">Adj meg egy legalább 8 karakteres új jelszót.</p>

              <form class="mt-5 space-y-4" @submit.prevent="handlePasswordUpdate">
                <div class="space-y-2">
                  <label class="text-sm font-semibold text-zinc-700" for="current-password">Jelenlegi jelszó</label>
                  <input id="current-password" v-model="passwordForm.current_password" type="password" class="brand-input" :disabled="isUpdatingPassword" required />
                </div>

                <div class="space-y-2">
                  <label class="text-sm font-semibold text-zinc-700" for="new-password">Új jelszó</label>
                  <input id="new-password" v-model="passwordForm.password" type="password" class="brand-input" :disabled="isUpdatingPassword" required minlength="8" />
                </div>

                <div class="space-y-2">
                  <label class="text-sm font-semibold text-zinc-700" for="new-password-confirm">Új jelszó újra</label>
                  <input id="new-password-confirm" v-model="passwordForm.password_confirmation" type="password" class="brand-input" :disabled="isUpdatingPassword" required minlength="8" />
                </div>

                <p v-if="passwordError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ passwordError }}</p>
                <p v-if="passwordSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ passwordSuccess }}</p>

                <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingPassword">
                  {{ isUpdatingPassword ? 'Mentés...' : 'Jelszó frissítése' }}
                </button>
              </form>
            </div>
          </div>
        </div>

        <div class="glass-panel p-8">
          <h2 class="mb-6 text-2xl font-bold text-zinc-900">Rendelések</h2>

          <div v-if="userOrders.length > 0" class="space-y-4">
            <div v-for="order in userOrders" :key="order.id" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
              <div class="flex items-start justify-between mb-4">
                <div>
                  <p class="text-sm font-semibold text-zinc-500 mb-1">Rendelés szám</p>
                  <h3 class="text-2xl font-bold text-zinc-900">{{ order.order_number }}</h3>
                </div>
                <div class="text-right">
                  <span :class="['px-4 py-2 rounded-lg font-semibold text-sm', getStatusColor(order.status)]">
                    {{ getStatusLabel(order.status) }}
                  </span>
                </div>
              </div>

              <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                  <p class="text-sm font-semibold text-zinc-600 mb-1">Dátum</p>
                  <p class="text-lg font-bold text-zinc-900">{{ formatDate(order.created_at) }}</p>
                </div>
                <div>
                  <p class="text-sm font-semibold text-zinc-600 mb-1">Tételek száma</p>
                  <p class="text-lg font-bold text-zinc-900">{{ order.items?.length || 0 }} db</p>
                </div>
                <div>
                  <p class="text-sm font-semibold text-zinc-600 mb-1">Összesen</p>
                  <p class="text-lg font-bold text-teal-700">{{ formatPrice(order.total) }} Ft</p>
                </div>
              </div>

              <div class="border-t border-zinc-200 pt-4">
                <p class="text-sm font-semibold text-zinc-600 mb-3">Tételek</p>
                <div class="space-y-2">
                  <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between rounded-lg bg-zinc-50 p-3">
                    <div>
                      <p class="font-semibold text-zinc-900">{{ item.product_name }}</p>
                      <p class="text-sm text-zinc-600">{{ item.product_brand }} - {{ item.quantity }} x {{ formatPrice(item.product_price) }} Ft</p>
                    </div>
                    <p class="font-bold text-zinc-900">{{ formatPrice(item.product_price * item.quantity) }} Ft</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div v-else class="rounded-2xl bg-white p-10 text-center shadow-sm">
            <h3 class="text-2xl font-bold text-zinc-900">
              Még nincs rendelésed
            </h3>
            <p class="mt-2 text-zinc-600">
              Kezdj el vásárolni és követsd nyomon itt a rendeléseidet!
            </p>
            <router-link to="/" class="btn-primary mt-4 inline-flex">
              Vissza a termékekhez
            </router-link>
          </div>
        </div>
      </section>
    </main>

    <BaseFooter />
  </div>
</template>

<route lang="yaml">
name: profile
meta:
  title: profile
  requiresAuth: true
</route>
