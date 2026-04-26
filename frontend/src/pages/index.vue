<script setup>
import { ref, onMounted } from 'vue'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import ProductCard from '@components/ProductCard.vue'
import BaseFooter from '@components/BaseFooter.vue'
import Toast from '@/components/ui/Toast.vue'
import TuningCompaniesSection from '@components/TuningCompaniesSection.vue'
import { useTuningProductStore } from '@stores/tuningProductStore'

const tuningProductStore = useTuningProductStore()

const toastVisible = ref(false)
const toastMessage = ref('')

onMounted(async () => {
    try {
        await tuningProductStore.fetchAllProducts()
    } catch (error) {
        console.error('Nem sikerült betölteni a termékeket:', error)
    }
})

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
                <TuningCompaniesSection />

                <div
                    v-if="tuningProductStore.isLoading"
                    class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm"
                >
                    Betöltés...
                </div>

                <div
                    v-else-if="tuningProductStore.error"
                    class="rounded-2xl bg-red-50 p-8 text-center font-semibold text-red-700 shadow-sm"
                >
                    {{ tuningProductStore.error }}
                </div>

                <div
                    v-else-if="tuningProductStore.products.length === 0"
                    class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm"
                >
                    Nincs megjeleníthető termék.
                </div>

                <div
                    v-else
                    class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3"
                >
                    <ProductCard
                        v-for="product in tuningProductStore.products"
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