<script setup>
import { computed, ref, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@/stores/cartStore'
import { useAuthStore } from '@/stores/authStore'
import { useTuningProductStore } from '@stores/tuningProductStore'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@/components/layout/SideMenu.vue'
import BaseFooter from '@/components/BaseFooter.vue'
import ProductCard from '@/components/ProductCard.vue'
import Toast from '@/components/ui/Toast.vue'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()
const tuningProductStore = useTuningProductStore()

const toastVisible = ref(false)
const toastMessage = ref('')

const productId = computed(() => Number(route.query.id))

const products = computed(() => tuningProductStore.products)

const product = computed(() => {
  return products.value.find(item => item.id === productId.value)
})

const relatedProducts = computed(() => {
  if (!product.value) {
    return []
  }

  const sameCategoryProducts = products.value
    .filter(item =>
      item.id !== product.value.id &&
      item.category === product.value.category
    )
    .slice(0, 3)

  if (sameCategoryProducts.length === 3) {
    return sameCategoryProducts
  }

  const otherProducts = products.value
    .filter(item =>
      item.id !== product.value.id &&
      !sameCategoryProducts.some(related => related.id === item.id)
    )
    .slice(0, 3 - sameCategoryProducts.length)

  return [
    ...sameCategoryProducts,
    ...otherProducts
  ]
})

onMounted(async () => {
  try {
    if (tuningProductStore.products.length === 0) {
      await tuningProductStore.fetchAllProducts()
    }
  } catch (error) {
    console.error('Nem sikerult betolteni a termekeket:', error)
  }
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('hu-HU').format(price)
}

const showToast = (product) => {
  toastMessage.value = `${product.name} hozzaadva a kosarhoz`
  toastVisible.value = true

  setTimeout(() => {
    toastVisible.value = false
  }, 2500)
}

const handleAddToCart = () => {
  if (!product.value) {
    return
  }

  if (!authStore.isAuthenticated) {
    router.push({
      path: '/login',
      query: {
        redirect: route.fullPath
      }
    })
    return
  }

  cartStore.addToCart(product.value)
  showToast(product.value)
}

const goBack = () => {
  router.push('/')
}
</script>

<template>
  <div class="min-h-screen">
    <BaseHeadLine />

    <Toast :show="toastVisible" :message="toastMessage" />

    <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
      <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
        <SideMenu />
      </aside>

      <section class="flex-1">
        <div v-if="tuningProductStore.isLoading" class="glass-panel p-10 text-center">
          <h2 class="text-3xl font-bold text-zinc-900">
            Betöltés...
          </h2>

          <p class="mt-3 text-zinc-600">
            A termék adatai betöltés alatt vannak.
          </p>
        </div>

        <div v-else-if="tuningProductStore.error" class="glass-panel p-10 text-center">
          <h2 class="text-3xl font-bold text-red-700">
            Hiba történt
          </h2>

          <p class="mt-3 text-zinc-600">
            {{ tuningProductStore.error }}
          </p>

          <button type="button"
            class="mt-6 rounded-xl bg-teal-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-800"
            @click="tuningProductStore.fetchAllProducts()">
            Újrapróbálom
          </button>
        </div>

        <div v-else-if="product" class="space-y-6">
          <div class="glass-panel p-5 md:p-6">
            <div class="grid gap-6 lg:grid-cols-[480px_1fr]">
              <div class="overflow-hidden rounded-2xl border border-zinc-200 bg-white">
                <img v-if="product.image" :src="product.image" :alt="product.name"
                  class="h-full min-h-[360px] w-full object-contain p-4">

                <div v-else class="flex min-h-[360px] items-center justify-center text-sm font-semibold text-zinc-400">
                  Nincs kép
                </div>
              </div>

              <div class="flex flex-col justify-between">
                <div>
                  <p v-if="product.badge"
                    class="mb-3 inline-block rounded-lg bg-orange-500 px-3 py-1 text-sm font-bold uppercase text-white">
                    {{ product.badge }}
                  </p>

                  <h1 class="text-4xl font-bold text-zinc-900">
                    {{ product.name }}
                  </h1>

                  <p class="mt-2 text-lg font-medium text-zinc-500">
                    {{ product.brand }}
                  </p>

                  <div class="mt-4 flex flex-wrap gap-2">
                    <span v-if="product.carBrand"
                      class="rounded-full bg-zinc-100 px-3 py-1 text-sm font-semibold text-zinc-700">
                      {{ product.carBrand }}
                    </span>

                    <span v-if="product.carModel"
                      class="rounded-full bg-zinc-100 px-3 py-1 text-sm font-semibold text-zinc-700">
                      {{ product.carModel }}
                    </span>

                    <span v-if="product.category"
                      class="rounded-full bg-teal-50 px-3 py-1 text-sm font-semibold text-teal-700">
                      {{ product.category }}
                    </span>
                  </div>

                  <div class="mt-6 flex items-center gap-3">
                    <span v-if="product.oldPrice" class="text-lg text-zinc-400 line-through">
                      {{ formatPrice(product.oldPrice) }} Ft
                    </span>

                    <span class="text-4xl font-extrabold text-teal-700">
                      {{ formatPrice(product.price) }} Ft
                    </span>
                  </div>

                  <p class="mt-4 text-base font-semibold"
                    :class="product.isInStock ? 'text-emerald-600' : 'text-orange-600'">
                    {{ product.stockText }}
                  </p>

                  <div class="mt-6 rounded-2xl border border-zinc-200 bg-white p-4 text-zinc-700 shadow-sm">
                    <h2 class="mb-2 text-xl font-bold text-zinc-900">
                      Termékleírás
                    </h2>

                    <p>
                      {{ product.description || 'Minosegi tuning termek, sportos es utcai felhasznalasra is alkalmas kivitelben.' }}
                    </p>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                  <button type="button"
                    class="rounded-xl border border-zinc-200 bg-zinc-100 px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-200"
                    @click="goBack">
                    Vissza
                  </button>

                  <button type="button"
                    class="rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-800"
                    @click="handleAddToCart">
                    Kosárba
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="glass-panel p-5 md:p-6">
            <h2 class="mb-5 text-2xl font-bold text-zinc-900">
              Hasonló termékek
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
              <ProductCard v-for="relatedProduct in relatedProducts" :key="relatedProduct.id" :product="relatedProduct"
                @added-to-cart="showToast" />
            </div>
          </div>
        </div>

        <div v-else class="glass-panel p-10 text-center">
          <h2 class="text-3xl font-bold text-zinc-900">
            A termék nem található
          </h2>

          <p class="mt-3 text-zinc-600">
            Ellenőrizd a linket vagy válassz másik terméket.
          </p>

          <button type="button"
            class="mt-6 rounded-xl bg-teal-700 px-5 py-3 text-sm font-semibold text-white transition hover:bg-teal-800"
            @click="goBack">
            Vissza a főoldalra
          </button>
        </div>
      </section>
    </main>

    <BaseFooter />
  </div>
</template>

<route lang="yaml">
name: detailed
meta:
  title: RaceDistrict
</route>