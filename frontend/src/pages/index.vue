<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import ProductCard from '@components/ProductCard.vue'
import BaseFooter from '@components/BaseFooter.vue'
import Toast from '@/components/ui/Toast.vue'
import TuningCompaniesSection from '@components/TuningCompaniesSection.vue'
import { useTuningProductStore } from '@stores/tuningProductStore'
import { useRoute, useRouter } from 'vue-router'

const tuningProductStore = useTuningProductStore()
const route = useRoute()
const router = useRouter()

const toastVisible = ref(false)
const toastMessage = ref('')
const currentPage = ref(1)
const itemsPerPage = 6

const filterForm = ref({
    search: '',
    min_price: '',
    max_price: '',
    is_in_stock: false,
    only_discounted: false,
    sort: ''
})

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

const getProductFilters = () => {
    const filters = {}

    if (route.query.service_category_id) {
        filters.service_category_id = route.query.service_category_id
    }

    if (route.query.brand_id) {
        filters.brand_id = route.query.brand_id
    }

    if (route.query.car_model_id) {
        filters.car_model_id = route.query.car_model_id
    }

    if (filterForm.value.search.trim()) {
        filters.search = filterForm.value.search.trim()
    }

    if (filterForm.value.min_price) {
        filters.min_price = filterForm.value.min_price
    }

    if (filterForm.value.max_price) {
        filters.max_price = filterForm.value.max_price
    }

    if (filterForm.value.is_in_stock) {
        filters.is_in_stock = true
    }

    if (filterForm.value.only_discounted) {
        filters.only_discounted = true
    }

    if (filterForm.value.sort) {
        filters.sort = filterForm.value.sort
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

const applyFilters = async () => {
    await loadProducts()
}

const resetFilters = async () => {
    filterForm.value = {
        search: '',
        min_price: '',
        max_price: '',
        is_in_stock: false,
        only_discounted: false,
        sort: ''
    }

    await router.push({
        path: route.path,
        query: {}
    })

    await loadProducts()
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

watch(
    () => [route.query.service_category_id, route.query.brand_id, route.query.car_model_id],
    async () => {
        await loadProducts()
    }
)

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
</script>

<template>
    <div class="landing-page min-h-screen overflow-x-hidden">
        <BaseHeadLine show-dark-toggle />

        <Toast :show="toastVisible" :message="toastMessage" />

        <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
            <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
                <SideMenu />
            </aside>

            <section class="flex-1 min-w-0 space-y-5 pb-14">
                <TuningCompaniesSection />

                <form class="glass-panel p-4 md:p-5" @submit.prevent="applyFilters">
                    <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                        <div>
                            <h2 class="text-2xl font-extrabold text-zinc-900">
                                Termékszűrő
                            </h2>
                            <p class="mt-1 text-sm font-medium text-zinc-500">
                                Szűrés név, ár, készlet és akció alapján.
                            </p>
                        </div>

                        <button type="button" class="btn-muted px-4 py-2" @click="resetFilters">
                            Szűrők törlése
                        </button>
                    </div>

                    <div class="grid grid-cols-1 gap-3 md:grid-cols-2 xl:grid-cols-4">
                        <div>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">
                                Keresés
                            </label>
                            <input v-model="filterForm.search" type="text" class="brand-input"
                                placeholder="Pl. turbó, Brembo, Audi">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">
                                Minimum ár
                            </label>
                            <input v-model.number="filterForm.min_price" type="number" min="0" class="brand-input"
                                placeholder="Pl. 50000">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">
                                Maximum ár
                            </label>
                            <input v-model.number="filterForm.max_price" type="number" min="0" class="brand-input"
                                placeholder="Pl. 300000">
                        </div>

                        <div>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">
                                Rendezés
                            </label>
                            <select v-model="filterForm.sort" class="brand-input">
                                <option value="">Alapértelmezett</option>
                                <option value="price_asc">Ár szerint növekvő</option>
                                <option value="price_desc">Ár szerint csökkenő</option>
                                <option value="name_asc">Név szerint A-Z</option>
                                <option value="name_desc">Név szerint Z-A</option>
                            </select>
                        </div>
                    </div>

                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        <div class="flex flex-wrap gap-3">
                            <label
                                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border px-4 py-3 text-sm font-semibold transition"
                                :class="filterForm.is_in_stock ? 'border-emerald-400 bg-emerald-50 text-emerald-800' : 'border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50'">
                                <input v-model="filterForm.is_in_stock" type="checkbox" class="h-4 w-4 accent-emerald-600">
                                Csak raktáron
                            </label>

                            <label
                                class="inline-flex cursor-pointer items-center gap-2 rounded-xl border px-4 py-3 text-sm font-semibold transition"
                                :class="filterForm.only_discounted ? 'border-emerald-400 bg-emerald-50 text-emerald-800' : 'border-zinc-200 bg-white text-zinc-700 hover:bg-zinc-50'">
                                <input v-model="filterForm.only_discounted" type="checkbox"
                                    class="h-4 w-4 accent-emerald-600">
                                Csak akciós
                            </label>
                        </div>

                        <button type="submit" class="btn-primary px-6 py-3">
                            Szűrés
                        </button>
                    </div>
                </form>

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