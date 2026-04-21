<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
import ProductCard from '@components/ProductCard.vue';
import BaseFooter from '@components/BaseFooter.vue';

const products = [
    {
        id: 1,
        name: 'Állítható sportfutómű Audi A3',
        brand: 'TA Technix',
        image: '',
        badge: 'Újdonság',
        oldPrice: 126000,
        price: 103790,
        stockText: 'Raktáron'
    },
    {
        id: 2,
        name: 'Sportülés fekete',
        brand: 'Bride',
        image: '',
        badge: 'Akció',
        oldPrice: 94670,
        price: 81630,
        stockText: 'Raktáron'
    }
]
</script>

<template>
    <div class="landing-page">

            <BaseHeadLine/>


        <main class="mx-auto flex max-w-7xl flex-col gap-6 px-4 py-4 lg:flex-row">
            <aside class="w-full lg:w-[280px] xl:w-[300px] shrink-0">
                <SideMenu />
            </aside>

            <section class="flex-1">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </section>
        </main>

        <BaseFooter/>
    </div>
</template>

<route lang="yaml">
name: landing
meta:
  title: landing
</route>