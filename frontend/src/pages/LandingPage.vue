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
    },
    {
        id: 3,
        name: 'Garrett turbó',
        brand: 'Garrett',
        image: '',
        badge: 'Akció',
        oldPrice: 250000,
        price: 225000,
        stockText: 'Raktáron'
    },
    {
        id: 4,
        name: 'Légszűrő',
        brand: 'K&N',
        image: '',
        badge: 'Akció',
        oldPrice: 35000,
        price: 28500,
        stockText: 'Raktáron'
    },
    {
        id: 5,
        name: 'Led fényszóró BMW E46',
        brand: 'Osram',
        image: '',
        badge: 'Új',
        oldPrice: 32000,
        price: 30000,
        stockText: 'Raktáron'
    },
    {
        id: 6,
        name: 'Kipufogóvég',
        brand: 'RaceCarbon',
        image: '',
        badge: 'Akció',
        oldPrice: 15000,
        price: 1000,
        stockText: 'Raktáron'
    },
    {
        id: 7,
        name: 'Sportkesztyű',
        brand: 'Racing',
        image: '',
        badge: 'Akció',
        oldPrice: 5000,
        price: 4500,
        stockText: 'Raktáron'
    },
    {
        id: 8,
        name: '3 küllős kormány',
        brand: 'Momo',
        image: '',
        badge: 'Akció',
        oldPrice: 95000,
        price: 82000,
        stockText: 'Raktáron'
    },
    {
        id: 9,
        name: 'Rendszámtáblatartó',
        brand: 'HMN',
        image: '',
        badge: 'Akció',
        oldPrice: 6900,
        price: 6700,
        stockText: 'Raktáron'
    },
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