<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'
import { fetchMyOrders, updateProfileEmail, updateProfilePassword } from '@/lib/api'
import BaseFooter from '@/components/BaseFooter.vue'

const authStore = useAuthStore()
const router = useRouter()

const userOrders = ref([])
const ordersLoading = ref(false)
const ordersError = ref('')

const emailForm = ref({ email: '', current_password: '' })
const passwordForm = ref({ current_password: '', password: '', password_confirmation: '' })
const isUpdatingEmail = ref(false)
const isUpdatingPassword = ref(false)
const emailSuccess = ref('')
const emailError = ref('')
const passwordSuccess = ref('')
const passwordError = ref('')

const userName = computed(() => authStore.user?.name || authStore.user?.email?.split('@')[0] || 'Felhasználó')
const userEmail = computed(() => authStore.user?.email || 'email@example.com')
const totalSpent = computed(() => userOrders.value.reduce((sum, order) => sum + Number(order.total_price || 0), 0))

const goBack = () => router.push('/')
const formatPrice = (price) => new Intl.NumberFormat('hu-HU').format(price || 0)
const formatDate = (dateString) => new Date(dateString).toLocaleDateString('hu-HU', { year: 'numeric', month: 'long', day: 'numeric' })

const getStatusLabel = (status) => {
  if (status === 'new') return 'Új rendelés'
  if (status === 'completed') return 'Kiszállítva'
  if (status === 'processing') return 'Feldolgozás alatt'
  if (status === 'cancelled') return 'Törölve'
  return status
}

const loadOrders = async () => {
  ordersLoading.value = true
  ordersError.value = ''
  try {
    const response = await fetchMyOrders(authStore.token)
    userOrders.value = response.data || []
  } catch (error) {
    ordersError.value = error.message || 'Nem sikerült betölteni a rendeléseket.'
  } finally {
    ordersLoading.value = false
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
    if (updatedUser) authStore.user = { ...authStore.user, ...updatedUser }
    emailForm.value.current_password = ''
    emailSuccess.value = response?.data?.message || 'Email cím sikeresen frissítve.'
  } catch (err) {
    emailError.value = err?.message || 'Nem sikerült az email módosítása.'
  } finally {
    isUpdatingEmail.value = false
  }
}

const handlePasswordUpdate = async () => {
  passwordSuccess.value = ''
  passwordError.value = ''

  if (!passwordForm.value.current_password || !passwordForm.value.password || !passwordForm.value.password_confirmation) {
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
    passwordForm.value = { current_password: '', password: '', password_confirmation: '' }
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
      <button type="button" class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900" @click="goBack">← Vissza</button>

      <section>
        <div class="glass-panel mb-6 p-8">
          <h1 class="text-4xl font-bold text-zinc-900">{{ userName }}</h1>
          <p class="mt-2 text-lg text-zinc-600">{{ userEmail }}</p>
          <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"><p class="text-sm font-semibold text-zinc-600 mb-1">Összes rendelés</p><p class="text-lg font-bold text-zinc-900">{{ userOrders.length }} db</p></div>
            <div class="rounded-xl border border-zinc-200 bg-white p-4 shadow-sm"><p class="text-sm font-semibold text-zinc-600 mb-1">Teljes költés</p><p class="text-lg font-bold text-teal-700">{{ formatPrice(totalSpent) }} Ft</p></div>
          </div>
        </div>

        <div class="glass-panel mb-6 p-8">
          <h2 class="mb-6 text-2xl font-bold text-zinc-900">Profil beállítások</h2>
          <div class="grid grid-cols-1 gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
              <h3 class="text-xl font-bold text-zinc-900">Email cím módosítása</h3>
              <form class="mt-5 space-y-4" @submit.prevent="handleEmailUpdate">
                <input v-model="emailForm.email" type="email" class="brand-input" :disabled="isUpdatingEmail" required />
                <input v-model="emailForm.current_password" type="password" class="brand-input" placeholder="Jelenlegi jelszó" :disabled="isUpdatingEmail" required />
                <p v-if="emailError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ emailError }}</p>
                <p v-if="emailSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ emailSuccess }}</p>
                <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingEmail">{{ isUpdatingEmail ? 'Mentés...' : 'Email frissítése' }}</button>
              </form>
            </div>
            <div class="rounded-2xl border border-zinc-200 bg-white p-6">
              <h3 class="text-xl font-bold text-zinc-900">Jelszó módosítása</h3>
              <form class="mt-5 space-y-4" @submit.prevent="handlePasswordUpdate">
                <input v-model="passwordForm.current_password" type="password" class="brand-input" placeholder="Jelenlegi jelszó" :disabled="isUpdatingPassword" required />
                <input v-model="passwordForm.password" type="password" class="brand-input" placeholder="Új jelszó" :disabled="isUpdatingPassword" required minlength="8" />
                <input v-model="passwordForm.password_confirmation" type="password" class="brand-input" placeholder="Új jelszó újra" :disabled="isUpdatingPassword" required minlength="8" />
                <p v-if="passwordError" class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm font-medium text-red-700">{{ passwordError }}</p>
                <p v-if="passwordSuccess" class="rounded-lg border border-green-200 bg-green-50 px-3 py-2 text-sm font-medium text-green-700">{{ passwordSuccess }}</p>
                <button class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-70" type="submit" :disabled="isUpdatingPassword">{{ isUpdatingPassword ? 'Mentés...' : 'Jelszó frissítése' }}</button>
              </form>
            </div>
          </div>
        </div>

        <div class="glass-panel p-8">
          <h2 class="mb-6 text-2xl font-bold text-zinc-900">Rendelések</h2>
          <div v-if="ordersLoading" class="rounded-2xl bg-white p-10 text-center font-semibold text-zinc-600">Rendelések betöltése...</div>
          <div v-else-if="ordersError" class="rounded-2xl bg-red-50 p-10 text-center font-semibold text-red-700">{{ ordersError }}</div>
          <div v-else-if="userOrders.length > 0" class="space-y-4">
            <div v-for="order in userOrders" :key="order.id" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm">
              <div class="flex items-start justify-between gap-3 mb-4">
                <div><p class="text-sm font-semibold text-zinc-500 mb-1">Rendelés szám</p><h3 class="text-2xl font-bold text-zinc-900">#{{ order.id }}</h3></div>
                <span class="rounded-lg bg-yellow-100 px-4 py-2 text-sm font-semibold text-yellow-800">{{ getStatusLabel(order.status) }}</span>
              </div>
              <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div><p class="text-sm font-semibold text-zinc-600 mb-1">Dátum</p><p class="text-lg font-bold text-zinc-900">{{ formatDate(order.created_at) }}</p></div>
                <div><p class="text-sm font-semibold text-zinc-600 mb-1">Tételek száma</p><p class="text-lg font-bold text-zinc-900">{{ order.items?.length || 0 }} db</p></div>
                <div><p class="text-sm font-semibold text-zinc-600 mb-1">Összesen</p><p class="text-lg font-bold text-teal-700">{{ formatPrice(order.total_price) }} Ft</p></div>
              </div>
              <div class="border-t border-zinc-200 pt-4"><p class="text-sm font-semibold text-zinc-600 mb-3">Tételek</p><div class="space-y-2"><div v-for="item in order.items" :key="item.id" class="flex items-center justify-between rounded-lg bg-zinc-50 p-3"><div><p class="font-semibold text-zinc-900">{{ item.product_name }}</p><p class="text-sm text-zinc-600">{{ item.tuning_company }} - {{ item.quantity }} x {{ formatPrice(item.unit_price) }} Ft</p></div><p class="font-bold text-zinc-900">{{ formatPrice(item.subtotal) }} Ft</p></div></div></div>
            </div>
          </div>
          <div v-else class="rounded-2xl bg-white p-10 text-center shadow-sm"><h3 class="text-2xl font-bold text-zinc-900">Még nincs rendelésed</h3><p class="mt-2 text-zinc-600">Kezdj el vásárolni és kövesd nyomon itt a rendeléseidet!</p><router-link to="/" class="btn-primary mt-4 inline-flex">Vissza a termékekhez</router-link></div>
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
