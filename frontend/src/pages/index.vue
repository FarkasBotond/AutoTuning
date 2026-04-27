<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import ProductCard from '@components/ProductCard.vue'
import BaseFooter from '@components/BaseFooter.vue'
import Toast from '@/components/ui/Toast.vue'
import TuningCompaniesSection from '@components/TuningCompaniesSection.vue'
import { useTuningProductStore } from '@stores/tuningProductStore'
import { useRoute } from 'vue-router'

const tuningProductStore = useTuningProductStore()
const route = useRoute()

const toastVisible = ref(false)
const toastMessage = ref('')
const currentPage = ref(1)
const itemsPerPage = 6

const totalPages = computed(() => {
    const total = Math.ceil(tuningProductStore.products.length / itemsPerPage)
    return total > 0 ? total : 1
})

const paginatedProducts = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage
    return tuningProductStore.products.slice(start, start + itemsPerPage)
})

const canGoPrev = computed(() => currentPage.value > 1)
const canGoNext = computed(() => currentPage.value < totalPages.value)

const goPrevPage = () => {
    if (!canGoPrev.value) {
        return
    }

    currentPage.value -= 1
}

const goNextPage = () => {
    if (!canGoNext.value) {
        return
    }

    currentPage.value += 1
}

watch(
    () => tuningProductStore.products.length,
    () => {
        currentPage.value = 1
    }
)

watch(totalPages, (newTotalPages) => {
    if (currentPage.value > newTotalPages) {
        currentPage.value = newTotalPages
    }
})

onMounted(async () => {
    await loadProducts()
})

const showToast = (product) => {
    toastMessage.value = `${product.name} hozzáadva a kosárhoz`
    toastVisible.value = true

    setTimeout(() => {
        toastVisible.value = false
    }, 2500)
}

const getProductFilters = () => {
    const filters = {}

    if (route.query.service_category_id) {
        filters.service_category_id = route.query.service_category_id
    }

    if (route.query.car_model_id) {
        filters.car_model_id = route.query.car_model_id
    }

    return filters
}

const loadProducts = async () => {
    try {
        await tuningProductStore.fetchAllProducts(getProductFilters())
        currentPage.value = 1
    } catch (error) {
        console.error('Nem sikerült betölteni a termékeket:', error)
    }
}
watch(
    () => [route.query.service_category_id, route.query.car_model_id],
    async () => {
        await loadProducts()
    }
)
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

                <div v-if="tuningProductStore.isLoading"
                    class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
                    Betöltés...
                </div>

                <div v-else-if="tuningProductStore.error"
                    class="rounded-2xl bg-red-50 p-8 text-center font-semibold text-red-700 shadow-sm">
                    {{ tuningProductStore.error }}
                </div>

                <div v-else-if="tuningProductStore.products.length === 0"
                    class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
                    Nincs megjeleníthető termék.
                </div>

                <div v-else class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <ProductCard v-for="product in paginatedProducts" :key="product.id" :product="product"
                        @added-to-cart="showToast" />
                </div>

                <div v-if="tuningProductStore.products.length > itemsPerPage"
                    class="flex items-center justify-center gap-3 pt-2">
                    <button type="button" class="btn-muted px-4 py-2 disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="!canGoPrev" @click="goPrevPage">
                        Előző
                    </button>

                    <span
                        class="rounded-xl border border-zinc-200 bg-white px-3 py-2 text-sm font-semibold text-zinc-700">
                        {{ currentPage }} / {{ totalPages }}
                    </span>

                    <button type="button" class="btn-muted px-4 py-2 disabled:cursor-not-allowed disabled:opacity-60"
                        :disabled="!canGoNext" @click="goNextPage">
                        Következő
                    </button>
                </div>
            </section>
        </main>

        <BaseFooter />
    </div>
</template>