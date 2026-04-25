<script setup>
import { ref } from 'vue'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import ProductCard from '@components/ProductCard.vue'
import BaseFooter from '@components/BaseFooter.vue'
import Toast from '@/components/ui/Toast.vue'
import { products } from '@/lib/mockProducts'
import TuningCompaniesSection from '@components/TuningCompaniesSection.vue'


const toastVisible = ref(false)
const toastMessage = ref('')

const showToast = (product) => {
    toastMessage.value = `${product.name} hozzáadva a kosárhoz`
    toastVisible.value = true

    setTimeout(() => {
        toastVisible.value = false
    }, 2500)
}
</script>

<template>
    <div class="landing-page min-h-screen bg-zinc-300">
        <BaseHeadLine />

        <Toast :show="toastVisible" :message="toastMessage" />

        <main class="flex gap-6 px-8 py-4">
            <aside class="w-[280px] shrink-0">
                <SideMenu />
            </aside>

            <section class="flex-1 rounded-2xl p-4 !mb-20">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <ProductCard
                        v-for="product in products"
                        :key="product.id"
                        :product="product"
                        @added-to-cart="showToast"
                    />
                </div>
            </section>
            
        </main>

        <BaseFooter />
    </div>
</template>