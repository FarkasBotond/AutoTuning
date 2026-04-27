<script setup>
import { computed, onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useBrandStore } from '@stores/brandStore'
import { useModelStore } from '@stores/modelStore'

const emit = defineEmits(['select-category', 'select-brand', 'select-model'])

const route = useRoute()
const router = useRouter()
const brandStore = useBrandStore()
const modelStore = useModelStore()

const activeTab = ref('categories')
const openedBrandId = ref(null)
const loadError = ref(null)

const categories = [
    {
        id: 1,
        title: 'Motor és teljesítménynövelés',
    },
    {
        id: 2,
        title: 'Kipufogó és szívórendszer',
    },
    {
        id: 3,
        title: 'Futómű és kormányzás',
    },
    {
        id: 4,
        title: 'Fékek',
    },
    {
        id: 5,
        title: 'Felnik, gumik, nyomtávszélesítők',
    },
    {
        id: 6,
        title: 'Külső kiegészítők',
    },
    {
        id: 7,
        title: 'Belső tér',
    },
    {
        id: 8,
        title: 'Világítás és elektronika',
    },
    {
        id: 9,
        title: 'Szerviz és karbantartás',
    },
    {
        id: 10,
        title: 'Univerzális kiegészítők',
    },
]

const brands = computed(() => {
    return [...brandStore.brands].sort((firstBrand, secondBrand) => {
        return firstBrand.name.localeCompare(secondBrand.name, 'hu')
    })
})

const selectedCategoryId = computed(() => {
    return Number(route.query.service_category_id || 0)
})

const selectedBrandId = computed(() => {
    return Number(route.query.brand_id || 0)
})

const selectedModelId = computed(() => {
    return Number(route.query.car_model_id || 0)
})

const isLoading = computed(() => {
    return brandStore.isLoading || modelStore.isLoading
})

onMounted(async () => {
    loadError.value = null

    try {
        await Promise.all([
            brandStore.brands.length ? Promise.resolve() : brandStore.fetchAllBrands(),
            modelStore.models.length ? Promise.resolve() : modelStore.fetchAllModels(),
        ])

        if (selectedBrandId.value) {
            openedBrandId.value = selectedBrandId.value
            activeTab.value = 'brands'
        }
    } catch (error) {
        loadError.value = 'Nem sikerült betölteni a gyártókat és modelleket.'
        console.error('Nem sikerült betölteni a gyártókat és modelleket:', error)
    }
})

const getModelsByBrandId = (brandId) => {
    return modelStore.models
        .filter(model => Number(model.brand_id) === Number(brandId))
        .sort((firstModel, secondModel) => {
            return getModelName(firstModel).localeCompare(getModelName(secondModel), 'hu')
        })
}

const getModelName = (model) => {
    return model.model || model.name || 'Ismeretlen modell'
}

const updateQuery = (newValues = {}) => {
    const nextQuery = {
        service_category_id: route.query.service_category_id,
        brand_id: route.query.brand_id,
        car_model_id: route.query.car_model_id,
        ...newValues,
    }

    Object.keys(nextQuery).forEach((key) => {
        if (
            nextQuery[key] === null ||
            nextQuery[key] === undefined ||
            nextQuery[key] === ''
        ) {
            delete nextQuery[key]
        }
    })

    router.push({
        path: '/',
        query: nextQuery,
    })
}

const handleCategoryClick = (category) => {
    const isSelected = selectedCategoryId.value === category.id
    emit('select-category', isSelected ? null : category)

    updateQuery({
        service_category_id: isSelected ? null : category.id,
    })
}

const handleBrandClick = (brand) => {
    const isSelected = selectedBrandId.value === brand.id || openedBrandId.value === brand.id
    openedBrandId.value = isSelected ? null : brand.id

    emit('select-brand', isSelected ? null : brand)

    updateQuery({
        brand_id: isSelected ? null : brand.id,
        car_model_id: null,
    })
}

const handleModelClick = (model) => {
    emit('select-model', model)

    updateQuery({
        brand_id: model.brand_id,
        car_model_id: model.id,
    })
}
</script>

<template>
    <aside class="glass-panel w-full overflow-hidden">
        <div class="grid grid-cols-2 border-b border-zinc-200 bg-zinc-50/80">
            <button @click="activeTab = 'categories'"
                class="relative px-3 py-4 text-center text-base font-bold uppercase tracking-wide transition md:text-lg"
                :class="activeTab === 'categories'
                    ? 'text-zinc-900'
                    : 'text-zinc-500 hover:text-zinc-900'">
                Kategóriák
                <span v-if="activeTab === 'categories'"
                    class="absolute bottom-0 left-1/2 h-[3px] w-3/4 -translate-x-1/2 rounded-full bg-teal-600"></span>
            </button>

            <button @click="activeTab = 'brands'"
                class="relative border-l border-zinc-200 px-3 py-4 text-center text-base font-bold uppercase tracking-wide transition md:text-lg"
                :class="activeTab === 'brands'
                    ? 'text-zinc-900'
                    : 'text-zinc-500 hover:text-zinc-900'">
                Gyártók
                <span v-if="activeTab === 'brands'"
                    class="absolute bottom-0 left-1/2 h-[3px] w-3/4 -translate-x-1/2 rounded-full bg-teal-600"></span>
            </button>
        </div>

        <div class="max-h-[720px] overflow-y-auto p-3">
            <div v-if="activeTab === 'categories'" class="space-y-3">
                <button v-for="item in categories" :key="item.id" type="button"
                    class="group relative flex h-[72px] w-full cursor-pointer items-center overflow-hidden rounded-xl border bg-white px-4 text-left transition duration-200 hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50"
                    :class="selectedCategoryId === item.id
                        ? 'border-teal-300 bg-teal-50'
                        : 'border-zinc-200'"
                    :style="item.image ? { backgroundImage: `url(${item.image})`, backgroundSize: 'cover', backgroundPosition: 'center' } : {}"
                    @click="handleCategoryClick(item)">
                    <div class="absolute inset-0 bg-black/30" :class="item.image ? '' : 'hidden'"></div>

                    <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-teal-600 transition"
                        :class="selectedCategoryId === item.id ? 'opacity-100' : 'opacity-0 group-hover:opacity-100'">
                    </div>

                    <span class="relative z-10 pr-3 text-base font-bold leading-tight text-zinc-800 md:text-lg"
                        :class="item.image ? 'text-white' : ''">
                        {{ item.title }}
                    </span>
                </button>
            </div>

            <div v-else class="space-y-2">
                <div v-if="isLoading"
                    class="rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm font-semibold text-zinc-500">
                    Betöltés...
                </div>

                <div v-else-if="loadError"
                    class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                    {{ loadError }}
                </div>

                <div v-else-if="brands.length === 0"
                    class="rounded-xl border border-zinc-200 bg-white px-4 py-3 text-sm font-semibold text-zinc-500">
                    Nincs elérhető gyártó.
                </div>

                <template v-else>
                    <div v-for="brand in brands" :key="brand.id" class="space-y-2">
                        <button type="button"
                            class="flex w-full items-center justify-between rounded-xl border bg-white px-4 py-3 text-left text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50 hover:text-zinc-900"
                            :class="selectedBrandId === brand.id && openedBrandId === brand.id
                                ? 'border-teal-300 bg-teal-50 text-zinc-900'
                                : 'border-zinc-200'" @click="handleBrandClick(brand)">
                            <span>{{ brand.name }}</span>

                            <span class="text-lg leading-none text-zinc-400 transition duration-200"
                                :class="openedBrandId === brand.id ? 'rotate-180 text-teal-700' : ''">
                                ˅
                            </span>
                        </button>

                        <Transition name="model-drop">
                            <div v-if="openedBrandId === brand.id" class="ml-5 space-y-2 overflow-hidden">
                                <button v-for="model in getModelsByBrandId(brand.id)" :key="model.id" type="button"
                                    class="w-full rounded-xl border bg-white px-4 py-3 text-left text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50 hover:text-zinc-900"
                                    :class="selectedModelId === model.id
                                        ? 'border-teal-300 bg-teal-50 text-zinc-900'
                                        : 'border-zinc-200'" @click.stop="handleModelClick(model)">
                                    {{ getModelName(model) }}
                                </button>

                                <div v-if="getModelsByBrandId(brand.id).length === 0"
                                    class="rounded-xl border border-dashed border-zinc-300 bg-zinc-50 px-4 py-3 text-sm font-semibold text-zinc-500">
                                    Ehhez a gyártóhoz nincs modell.
                                </div>
                            </div>
                        </Transition>
                    </div>
                </template>
            </div>
        </div>
    </aside>
</template>

<style scoped>
.model-drop-enter-active,
.model-drop-leave-active {
    max-height: 3000px;
    opacity: 1;
    transform: translateY(0);
    transition:
        max-height 0.35s ease,
        opacity 0.25s ease,
        transform 0.25s ease;
}

.model-drop-enter-from,
.model-drop-leave-to {
    max-height: 0;
    opacity: 0;
    transform: translateY(-8px);
}
</style>
