<script setup>
import { computed, onMounted } from 'vue'
import { useAuthStore } from '@/stores/authStore'
import { useOrderStore } from '@/stores/orderStore'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@/components/layout/SideMenu.vue'
import BaseFooter from '@/components/BaseFooter.vue'

const authStore = useAuthStore()
const orderStore = useOrderStore()

onMounted(async () => {
  try {
    await orderStore.fetchUserOrders()
  } catch (err) {
    console.error('Failed to fetch orders:', err)
  }
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

const userOrders = computed(() => {
  return orderStore.orders
})

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
</script>

<template>
  <div class="min-h-screen bg-zinc-200">
    <BaseHeadLine />

    <main class="flex gap-6 px-8 py-4">
      <aside class="w-[280px] shrink-0">
        <SideMenu />
      </aside>

      <section class="flex-1">
        <!-- User Info Card -->
        <div class="rounded-3xl bg-zinc-100 p-8 shadow-sm mb-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h1 class="text-4xl font-bold text-zinc-900">{{ userName }}</h1>
              <p class="mt-2 text-lg text-zinc-600">{{ userEmail }}</p>
            </div>
            <div class="text-right">
              <div class="inline-block rounded-full bg-gradient-to-br from-blue-500 to-purple-600 p-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" class="h-12 w-12 text-white">
                  <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"/>
                </svg>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div class="rounded-xl bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Fiók létrehozva</p>
              <p class="text-lg font-bold text-zinc-900">{{ createdAt }}</p>
            </div>
            <div class="rounded-xl bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Összes rendelés</p>
              <p class="text-lg font-bold text-zinc-900">{{ userOrders.length }} db</p>
            </div>
            <div class="rounded-xl bg-white p-4 shadow-sm">
              <p class="text-sm font-semibold text-zinc-600 mb-1">Teljes költés</p>
              <p class="text-lg font-bold text-red-600">{{ formatPrice(userOrders.reduce((sum, order) => sum + order.total, 0)) }} Ft</p>
            </div>
          </div>
        </div>

        <!-- Orders Section -->
        <div class="rounded-3xl bg-zinc-100 p-8 shadow-sm">
          <h2 class="mb-6 text-2xl font-bold text-zinc-900">Rendelések</h2>

          <div v-if="userOrders.length > 0" class="space-y-4">
            <div v-for="order in userOrders" :key="order.id" class="rounded-2xl border border-zinc-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow">
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
                  <p class="text-lg font-bold text-red-600">{{ formatPrice(order.total) }} Ft</p>
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
            <router-link 
              to="/" 
              class="mt-4 inline-block rounded-lg bg-red-600 px-6 py-3 font-semibold text-white transition hover:bg-red-500"
            >
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
</route>
