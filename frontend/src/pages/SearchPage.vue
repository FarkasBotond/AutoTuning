<script setup>
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@/components/layout/SideMenu.vue'
import BaseFooter from '@/components/BaseFooter.vue'
import ProductCard from '@/components/ProductCard.vue'
import Toast from '@/components/ui/Toast.vue'


import allithatofutomuImg from '@/assets/images/allithatofutomu.jpg'
import ulesImg from '@/assets/images/ules.webp'
import legszuroImg from '@/assets/images/legszuro.jpg'
import ledizzoImg from '@/assets/images/ledizzo.webp'
import kipuvegImg from '@/assets/images/kipuveg.webp'
import turboImg from '@/assets/images/turbo.jpg'
import { computed, ref } from 'vue'
import { useRoute } from 'vue-router'
import { products } from '@/lib/mockProducts'

const route = useRoute()

const normalizeText = (text) => {
  return String(text)
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '')
}

const searchQuery = computed(() => {
  return normalizeText(route.query.q || '')
})

const filteredProducts = computed(() => {
  if (!searchQuery.value) {
    return []
  }

  return products.filter(product => {
    const searchableText = normalizeText(
      `${product.name} ${product.brand} ${product.description || ''}`
    )

    return searchableText.includes(searchQuery.value)
  })
})

const toastVisible = ref(false)
const toastMessage = ref('')

const showToast = (product) => {
    toastMessage.value = `${product.name} hozzáadva a kosárhoz`
    toastVisible.value = true

    setTimeout(() => {
        toastVisible.value = false
    }, 2500)
}

const filterOpen = ref(false)
const selectedBrand = ref('')
const minPrice = ref('')
const maxPrice = ref('')
const onlySale = ref(false)
const onlyInStock = ref(false)
</script>

<template>
  <div class="min-h-screen bg-zinc-200">
    <BaseHeadLine />

    <Toast :show="toastVisible" :message="toastMessage" />

    <main class="flex gap-6 px-8 py-4">
      <aside class="w-[280px] shrink-0">
        <SideMenu />
      </aside>

      <section class="flex-1 rounded-2xl bg-zinc-100 p-4">
        <div class="mb-4 flex items-center justify-between rounded-xl bg-zinc-200 px-4 py-3">
          <h2 class="text-3xl font-bold text-zinc-900">Keresési eredmények</h2>
          <p class="mt-1 text-sm text-zinc-600">
            Keresés erre: "{{ route.query.q }}"
          </p>
          <button type="button"
          @click="filerOpen =!filterOpen"
            class="inline-flex items-center gap-3 rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-red-600 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
              stroke="currentColor" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 6h9m-9 6h6m-6 6h3M4.5 4.5h3v3h-3v-3Zm0 6h3v3h-3v-3Zm0 6h3v3h-3v-3Z" />
            </svg>

            Szűrő
          </button>
        </div>

        <div v-if="filterOpen" class="mb-4 rounded-2xl border border-zinc-300 bg-white p-4 shadow-sm"></div>

        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" @added-to-cart="showToast"/>
        </div>

        <div v-else class="rounded-2xl bg-white p-10 text-center shadow-sm">
          <h3 class="text-2xl font-bold text-zinc-900">
            Nincs találat
          </h3>
          <p class="mt-2 text-zinc-600">
            Nem találtunk terméket erre: "{{ route.query.q }}"
          </p>
        </div>
      </section>
    </main>

    <BaseFooter />
  </div>
</template>

<route lang="yaml">
name: search
meta:
  title: search
</route>