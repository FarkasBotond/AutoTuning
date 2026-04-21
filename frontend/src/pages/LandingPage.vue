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


        <main>
            <SideMenu/>

            <ProductCard
            v-for="product in products"
            :key="product.id"
            :product="product"
            />
        </main>

        <BaseFooter/>
    </div>
</template>

<route lang="yaml">
name: landing
meta:
  title: Landing
</route>