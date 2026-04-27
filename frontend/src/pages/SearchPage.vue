<script setup>
import { computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'

import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import BaseFooter from '@components/BaseFooter.vue'
import ProductCard from '@/components/ProductCard.vue'
import { useTuningProductStore } from '@stores/tuningProductStore'

const route = useRoute()
const tuningProductStore = useTuningProductStore()

const normalizeText = (text) => {
  return String(text ?? '')
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
}

const searchText = computed(() => {
  return normalizeText(route.query.q).trim()
})

const filteredProducts = computed(() => {
  if (!searchText.value) {
    return tuningProductStore.products
  }

  return tuningProductStore.products.filter((product) => {
    const searchableText = normalizeText([
      product.name,
      product.brand,
      product.carBrand,
      product.carModel,
      product.category,
      product.stockText
    ]
      .filter(Boolean)
      .join(' '))

    return searchableText.includes(searchText.value)
  })
})

onMounted(async () => {
  if (tuningProductStore.products.length === 0) {
    try {
      await tuningProductStore.fetchAllProducts()
    } catch (error) {
      console.error('Nem sikerült betölteni a termékeket:', error)
    }
  }
})


</script>

<template>
  <div class="min-h-screen overflow-x-hidden">
    <BaseHeadLine />

    <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
      <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
        <SideMenu />
      </aside>

      <section class="flex-1 min-w-0 space-y-5 pb-14">
        <div class="glass-panel p-5 md:p-6">
          <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
            <div>
              <h1 class="section-title">
                Keresés
              </h1>

              <p class="mt-2 text-sm font-medium text-zinc-500">
                Találatok erre:
                <span class="font-bold text-zinc-800">
                  {{ searchText || 'összes termék' }}
                </span>
              </p>
            </div>

            <RouterLink to="/" class="btn-muted px-4 py-2 text-xs md:text-sm">
              Vissza
            </RouterLink>
          </div>

          <div v-if="tuningProductStore.isLoading"
            class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
            Betöltés...
          </div>

          <div v-else-if="tuningProductStore.error"
            class="rounded-2xl bg-red-50 p-8 text-center font-semibold text-red-700 shadow-sm">
            {{ tuningProductStore.error }}
          </div>

          <div v-else-if="filteredProducts.length === 0"
            class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
            Nincs találat a keresésre.
          </div>

          <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
            <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" />
          </div>
        </div>
      </section>
    </main>

    <BaseFooter />
  </div>
</template>

<route lang="yaml">
name: search
meta:
  title: Keresés
</route>