<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

const route = useRoute()
const router = useRouter()

const selectedCategoryId = computed(() => {
    return Number(route.query.service_category_id ?? 0)
})

const selectCategory = (categoryId) => {
    router.push({
        path: '/',
        query: {
            ...route.query,
            service_category_id: categoryId
        }
    })
}

const clearCategory = () => {
    const query = { ...route.query }
    delete query.service_category_id

    router.push({
        path: '/',
        query
    })
}
</script>

<template>
    <aside class="glass-panel w-full overflow-hidden">
        <div class="grid grid-cols-2 border-b border-zinc-200 bg-zinc-50/80">
            <button
                @click="activeTab = 'categories'"
                class="relative px-3 py-4 text-center text-base font-bold uppercase tracking-wide transition md:text-lg"
                :class="activeTab === 'categories'
                    ? 'text-zinc-900'
                    : 'text-zinc-500 hover:text-zinc-900'">
                Kategóriák
                <span
                    v-if="activeTab === 'categories'"
                    class="absolute bottom-0 left-1/2 h-[3px] w-3/4 -translate-x-1/2 rounded-full bg-teal-600"
                ></span>
            </button>

            <button
                @click="activeTab = 'brands'"
                class="relative border-l border-zinc-200 px-3 py-4 text-center text-base font-bold uppercase tracking-wide transition md:text-lg"
                :class="activeTab === 'brands'
                    ? 'text-zinc-900'
                    : 'text-zinc-500 hover:text-zinc-900'">
                Gyártók
                <span
                    v-if="activeTab === 'brands'"
                    class="absolute bottom-0 left-1/2 h-[3px] w-3/4 -translate-x-1/2 rounded-full bg-teal-600"
                ></span>
            </button>
        </div>

        <div class="p-3">
            <div v-if="activeTab === 'categories'" class="space-y-3">
                <div
                    v-for="item in categories"
                    :key="item.id"
                    class="group relative flex h-[72px] cursor-pointer items-center overflow-hidden rounded-xl border border-zinc-200 bg-white px-4 transition duration-200 hover:-translate-y-0.5 hover:border-teal-200 hover:bg-teal-50"
                    :style="item.image ? { backgroundImage: `url(${item.image})`, backgroundSize: 'cover', backgroundPosition: 'center' } : {}"
                >
                    <div
                        class="absolute inset-0 bg-black/30"
                        :class="item.image ? '' : 'hidden'"
                    ></div>

                    <div class="absolute inset-y-0 left-0 w-1 rounded-l-xl bg-teal-600 opacity-0 transition group-hover:opacity-100"></div>

                    <span class="relative z-10 pr-3 text-base font-bold leading-tight text-zinc-800 md:text-lg" :class="item.image ? 'text-white' : ''">
                        {{ item.title }}
                    </span>
                </div>
            </div>

            <div v-else class="grid grid-cols-1 gap-2">
                <button
                    v-for="brand in brands"
                    :key="brand"
                    type="button"
                    class="rounded-xl border border-zinc-200 bg-white px-4 py-3 text-left text-sm font-semibold text-zinc-700 transition hover:border-teal-200 hover:bg-teal-50 hover:text-zinc-900"
                >
                    {{ formatBrandName(brand) }}
                </button>
            </div>
        </div>
    </aside>
</template>