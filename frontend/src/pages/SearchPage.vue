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
  <div class="min-h-screen">
    <BaseHeadLine />

    <Toast :show="toastVisible" :message="toastMessage" />

    <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
      <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
        <SideMenu />
      </aside>

      <section class="flex-1 rounded-3xl bg-white/55 p-4 backdrop-blur-sm md:p-5">
        <div class="mb-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-zinc-200 bg-white px-4 py-3">
          <div>
            <h2 class="text-2xl font-extrabold text-zinc-900 md:text-3xl">Keresési eredmények</h2>
            <p class="mt-1 text-sm text-zinc-500">Keresés erre: "{{ route.query.q }}"</p>
          </div>
          <button type="button"
          @click="filterOpen = !filterOpen"
            class="btn-muted px-4 py-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
              stroke="currentColor" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 6h9m-9 6h6m-6 6h3M4.5 4.5h3v3h-3v-3Zm0 6h3v3h-3v-3Zm0 6h3v3h-3v-3Z" />
            </svg>

            Szűrő
          </button>
        </div>

        <div v-if="filterOpen" class="mb-4 rounded-2xl border border-zinc-200 bg-white p-4 text-sm text-zinc-500 shadow-sm">
          Szűrők hamarosan érkeznek.
        </div>

        <div v-if="filteredProducts.length > 0" class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <ProductCard v-for="product in filteredProducts" :key="product.id" :product="product" @added-to-cart="showToast"/>
        </div>

        <div v-else class="rounded-2xl border border-zinc-200 bg-white p-10 text-center shadow-sm">
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