<script setup="">
import BaseFooter from '@components/BaseFooter.vue';
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import { useCartStore } from '@stores/cartStore';

</script>

<template>
    <div class="landing-page">

        <BaseHeadLine />


        <main class="mx-auto flex max-w-[1450px] gap-6 px-4 py-4">
            <aside class="w-[280px] shrink-0">
                <SideMenu />
            </aside>

            <section class="flex-1 rounded-2xl bg-zinc-100 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <ProductCard v-for="product in products" :key="product.id" :product="product" />
                </div>
            </section>
        </main>

        <BaseFooter />
    </div>
</template>