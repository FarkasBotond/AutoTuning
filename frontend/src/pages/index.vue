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
    <div class="landing-page min-h-screen overflow-x-hidden">
        <BaseHeadLine />

        
        <Toast :show="toastVisible" :message="toastMessage" />

        <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
            <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
                <SideMenu />
            </aside>

            <section class="flex-1 min-w-0 space-y-5 pb-14">
                <TuningCompaniesSection/>
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