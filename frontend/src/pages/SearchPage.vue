<script setup>
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@/components/layout/SideMenu.vue'
import BaseFooter from '@/components/BaseFooter.vue'
import ProductCard from '@/components/ProductCard.vue'

import allithatofutomuImg from '@/assets/images/allithatofutomu.jpg'
import ulesImg from '@/assets/images/ules.webp'
import legszuroImg from '@/assets/images/legszuro.jpg'
import ledizzoImg from '@/assets/images/ledizzo.webp'
import kipuvegImg from '@/assets/images/kipuveg.webp'
import turboImg from '@/assets/images/turbo.jpg'

const products = [
  {
    id: 1,
    name: 'Állítható sportfutómű Audi A3',
    brand: 'TA Technix',
    image: allithatofutomuImg,
    badge: 'Újdonság',
    oldPrice: 126000,
    price: 103790,
    stockText: 'Raktáron'
  },
  {
    id: 2,
    name: 'Sportülés fekete',
    brand: 'Bride',
    image: ulesImg,
    badge: 'Akció',
    oldPrice: 94670,
    price: 81630,
    stockText: 'Raktáron'
  },
  {
    id: 3,
    name: 'Garrett turbó',
    brand: 'Garrett',
    image: turboImg,
    badge: 'Akció',
    oldPrice: 250000,
    price: 225000,
    stockText: 'Raktáron'
  },
  {
    id: 4,
    name: 'Légszűrő',
    brand: 'K&N',
    image: legszuroImg,
    badge: 'Akció',
    oldPrice: 35000,
    price: 28500,
    stockText: 'Raktáron'
  },
  {
    id: 5,
    name: 'Led fényszóró BMW E46',
    brand: 'Osram',
    image: ledizzoImg,
    badge: 'Új',
    oldPrice: 32000,
    price: 30000,
    stockText: 'Raktáron'
  },
  {
    id: 6,
    name: 'Kipufogóvég',
    brand: 'RaceCarbon',
    image: kipuvegImg,
    badge: 'Akció',
    oldPrice: 15000,
    price: 1000,
    stockText: 'Raktáron'
  },
]
</script>

<template>
  <div class="min-h-screen bg-zinc-200">
    <BaseHeadLine />

    <main class="mx-auto flex max-w-[1450px] gap-6 px-4 py-4">
      <aside class="w-[280px] shrink-0">
        <SideMenu />
      </aside>

      <section class="flex-1 rounded-2xl bg-zinc-100 p-4">
        <div class="mb-4 flex items-center justify-between rounded-xl bg-zinc-200 px-4 py-3">
          <h2 class="text-3xl font-bold text-zinc-900">Keresési eredmények</h2>

          <button type="button"
            class="inline-flex items-center gap-3 rounded-xl bg-zinc-900 px-6 py-3 text-sm font-semibold text-white shadow-md transition duration-200 hover:-translate-y-0.5 hover:bg-red-600 hover:shadow-lg">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
              stroke="currentColor" class="h-4 w-4">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M10.5 6h9m-9 6h6m-6 6h3M4.5 4.5h3v3h-3v-3Zm0 6h3v3h-3v-3Zm0 6h3v3h-3v-3Z" />
            </svg>

            Szűrő
          </button>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
          <ProductCard v-for="product in products" :key="product.id" :product="product" />
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