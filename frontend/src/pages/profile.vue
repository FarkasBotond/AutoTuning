<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { fetchMyOrders, updateProfileEmail, updateProfilePassword } from '@/lib/api'
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

const userOrders = ref([])
const isLoadingOrders = ref(false)
const orderError = ref('')
const isUpdatingEmail = ref(false)
const isUpdatingPassword = ref(false)
const emailSuccess = ref('')
const emailError = ref('')
const passwordSuccess = ref('')
const passwordError = ref('')

const goBack = () => {
  router.push('/')
}

const userName = computed(() => {
  return authStore.user?.name || authStore.user?.email?.split('@')[0] || 'Felhasználó'
})

const userEmail = computed(() => {
  return authStore.user?.email || 'email@example.com'
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('hu-HU').format(price || 0)
}

const formatDate = (dateString) => {
  if (!dateString) {
    return '-'
  }

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
    case 'new':
      return 'bg-teal-100 text-teal-800'
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
    case 'new':
      return 'Új rendelés'
    case 'pending':
      return 'Függőben'
    case 'cancelled':
      return 'Törölve'
    default:
      return status || 'Ismeretlen'
  }
}

const loadOrders = async () => {
  isLoadingOrders.value = true
  orderError.value = ''

  try {
    const response = await fetchMyOrders(authStore.token)
    userOrders.value = response.data || []
  } catch (error) {
    orderError.value = error.message || 'Nem sikerült betölteni a rendeléseket.'
  } finally {
    isLoadingOrders.value = false
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

onMounted(async () => {
  emailForm.value.email = authStore.user?.email || ''
  await loadOrders()
})
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

      <section class="space-y-6">
        <div class="glass-panel p-8">
          <h1 class="text-3xl font-extrabold text-zinc-900 md:text-4xl">
            Az én fiókom
          </h1>
          <p class="mt-2 text-zinc-600">
            Üdv, {{ userName }}! Itt kezelheted a profilodat és a rendeléseidet.
          </p>

          <div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-5">
            <p class="text-sm font-semibold text-zinc-500">Email cím</p>
            <p class="mt-1 text-lg font-bold text-zinc-900">{{ userEmail }}</p>
          </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
          <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="text-xl font-bold text-zinc-900">Email módosítása</h2>
            <p class="mt-1 text-sm text-zinc-600">A módosításhoz add meg a jelenlegi jelszavadat.</p>

            <form class="mt-5 space-y-4" @submit.prevent="handleEmailUpdate">
              <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700">Új email cím</label>
                <input v-model="emailForm.email" type="email" class="brand-input" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700">Jelenlegi jelszó</label>
                <input v-model="emailForm.current_password" type="password" class="brand-input" required>
              </div>

              <p v-if="emailError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ emailError }}</p>
              <p v-if="emailSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ emailSuccess }}</p>

              <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingEmail">
                {{ isUpdatingEmail ? 'Mentés...' : 'Email frissítése' }}
              </button>
            </form>
          </div>

          <div class="rounded-2xl border border-zinc-200 bg-white p-6">
            <h2 class="text-xl font-bold text-zinc-900">Jelszó módosítása</h2>
            <p class="mt-1 text-sm text-zinc-600">Adj meg egy legalább 8 karakteres új jelszót.</p>

            <form class="mt-5 space-y-4" @submit.prevent="handlePasswordUpdate">
              <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700">Jelenlegi jelszó</label>
                <input v-model="passwordForm.current_password" type="password" class="brand-input" required>
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700">Új jelszó</label>
                <input v-model="passwordForm.password" type="password" class="brand-input" required minlength="8">
              </div>

              <div>
                <label class="mb-2 block text-sm font-semibold text-zinc-700">Új jelszó újra</label>
                <input v-model="passwordForm.password_confirmation" type="password" class="brand-input" required minlength="8">
              </div>

              <p v-if="passwordError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ passwordError }}</p>
              <p v-if="passwordSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ passwordSuccess }}</p>

              <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingPassword">
                {{ isUpdatingPassword ? 'Mentés...' : 'Jelszó frissítése' }}
              </button>
            </form>
          </div>
        </div>

        <div class="glass-panel p-8">
          <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
            <div>
              <h2 class="text-2xl font-bold text-zinc-900">Rendelések</h2>
              <p class="mt-1 text-sm text-zinc-600">Itt jelennek meg a bejelentkezett fiókkal leadott rendelések.</p>
            </div>
            <button type="button" class="btn-muted px-4 py-2" @click="loadOrders">Frissítés</button>
          </div>

          <div v-if="isLoadingOrders" class="rounded-2xl bg-white p-10 text-center font-semibold text-zinc-600">
            Rendelések betöltése...
          </div>

          <div v-else-if="orderError" class="rounded-2xl border border-red-200 bg-red-50 p-5 text-sm font-semibold text-red-700">
            {{ orderError }}
          </div>

          <div v-else-if="userOrders.length > 0" class="space-y-4">
            <div v-for="order in userOrders" :key="order.id" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm transition-shadow hover:shadow-md">
              <div class="mb-4 flex items-start justify-between gap-4">
                <div>
                  <p class="mb-1 text-sm font-semibold text-zinc-500">Rendelési azonosító</p>
                  <h3 class="text-2xl font-bold text-zinc-900">#{{ order.id }}</h3>
                </div>
                <span :class="['rounded-lg px-4 py-2 text-sm font-semibold', getStatusColor(order.status)]">
                  {{ getStatusLabel(order.status) }}
                </span>
              </div>

              <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                  <p class="mb-1 text-sm font-semibold text-zinc-600">Dátum</p>
                  <p class="text-lg font-bold text-zinc-900">{{ formatDate(order.created_at) }}</p>
                </div>
                <div>
                  <p class="mb-1 text-sm font-semibold text-zinc-600">Tételek száma</p>
                  <p class="text-lg font-bold text-zinc-900">{{ order.items?.length || 0 }} db</p>
                </div>
                <div>
                  <p class="mb-1 text-sm font-semibold text-zinc-600">Összesen</p>
                  <p class="text-lg font-bold text-teal-700">{{ formatPrice(order.total_price) }} Ft</p>
                </div>
              </div>

              <div class="border-t border-zinc-200 pt-4">
                <p class="mb-3 text-sm font-semibold text-zinc-600">Tételek</p>
                <div class="space-y-2">
                  <div v-for="item in order.items" :key="item.id" class="flex items-center justify-between rounded-lg bg-zinc-50 p-3">
                    <div>
                      <p class="font-semibold text-zinc-900">{{ item.product_name }}</p>
                      <p class="text-sm text-zinc-600">{{ item.tuning_company }} - {{ item.quantity }} x {{ formatPrice(item.unit_price) }} Ft</p>
                    </div>
                    <p class="font-bold text-zinc-900">{{ formatPrice(item.subtotal) }} Ft</p>
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
              Bejelentkezett fiókkal leadott rendelések itt fognak megjelenni.
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
  title: Profil
  requiresAuth: true
</route>
