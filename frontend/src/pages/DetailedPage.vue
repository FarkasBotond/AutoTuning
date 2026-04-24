<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
import { computed } from 'vue'
import { useRoute } from 'vue-router';
import BaseFooter from '@components/BaseFooter.vue';
import { products } from '@/lib/mockProducts';

const route = useRoute()

const product = computed(() => {
  const id = Number(route.query.id)
  return products.find(item => item.id === id)
})

const formatPrice = (price) => {
  return new Intl.NumberFormat('hu-HU').format(price)
}

</script>

<template>
  <div class="min-h-screen bg-zinc-200">
    <BaseHeadLine />

    <main class="mx-auto flex max-w-[1450px] flex-col gap-6 px-4 py-4 lg:flex-row">
      <aside class="w-full shrink-0 lg:w-[280px]">
        <SideMenu />
      </aside>

      <section class="flex-1">
        <div v-if="product" class="space-y-6">
          <div class="rounded-3xl bg-zinc-100 p-5 shadow-sm">
            <div class="grid gap-6 lg:grid-cols-[480px_1fr]">
              <div class="overflow-hidden rounded-2xl bg-white">
                <img :src="product.image" :alt="product.name" class="h-full w-full object-cover">
              </div>

              <div class="flex flex-col justify-between">
                <div>
                  <p v-if="product.badge"
                    class="mb-3 inline-block rounded-lg bg-red-600 px-3 py-1 text-sm font-bold uppercase text-white">
                    {{ product.badge }}
                  </p>

                  <h1 class="text-4xl font-bold text-zinc-900">
                    {{ product.name }}
                  </h1>

                  <p class="mt-2 text-lg font-medium text-zinc-500">
                    {{ product.brand }}
                  </p>

                  <div class="mt-6 flex items-center gap-3">
                    <span v-if="product.oldPrice" class="text-lg text-zinc-400 line-through">
                      {{ formatPrice(product.oldPrice) }} Ft
                    </span>

                    <span class="text-4xl font-extrabold text-red-600">
                      {{ formatPrice(product.price) }} Ft
                    </span>
                  </div>

                  <p class="mt-4 text-base font-semibold text-green-600">
                    {{ product.stockText }}
                  </p>

                  <div class="mt-6 rounded-2xl bg-white p-4 text-zinc-700 shadow-sm">
                    <h2 class="mb-2 text-xl font-bold text-zinc-900">
                      Termékleírás
                    </h2>

                    <p>
                      {{ product.description || 'Minosegi termek, sportos es utcai felhasznalasra is alkalmas kivitelben.' }}
                    </p>
                  </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3 sm:grid-cols-2">
                  <button type="button"
                    class="rounded-xl bg-zinc-900 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-zinc-800">
                    Megnézem
                  </button>

                  <button type="button"
                    class="rounded-xl bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-500"
                    @click="handleAddToCart">
                    Kosárba
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="rounded-3xl bg-zinc-100 p-5 shadow-sm">
            <h2 class="mb-5 text-2xl font-bold text-zinc-900">
              Hasonló termékek
            </h2>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
              <ProductCard v-for="relatedProduct in relatedProducts" :key="relatedProduct.id"
                :product="relatedProduct" />
            </div>
          </div>
        </div>

        <div v-else class="rounded-3xl bg-zinc-100 p-10 text-center shadow-sm">
          <h2 class="text-3xl font-bold text-zinc-900">
            A termék nem található
          </h2>

          <p class="mt-3 text-zinc-600">
            Ellenőrizd a linket vagy válassz másik terméket.
          </p>
        </div>
      </section>
    </main>

    <BaseFooter />
  </div>
</template>


<route lang="yaml">
name: detailed
meta:
  title: detailed
</route>