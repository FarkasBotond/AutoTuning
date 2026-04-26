<script setup>
import { ref } from 'vue'

const activeTab = ref('categories')

const categories = [
    {
        id: 1,
        title: 'Motor és teljesítménynövelés',
        image: '',
    },
    {
        id: 2,
        title: 'Kipufogó és szívórendszer',
        image:'',
    },
    {
        id: 3,
        title: 'Futómű és kormányzás',
        image:'',
    },
    {
        id: 4,
        title: 'Fékek',
        image:'',
    },
    {
        id: 5,
        title: 'Felnik, gumik, nyomtávszélesítők',
        image:'',
    },
    {
        id: 6,
        title: 'Külső kiegészítők',
        image:'',
    },
    {
        id: 7,
        title: 'Belső tér',
        image:'',
    },
    {
        id: 8,
        title: 'Világítás és elektronika',
        image:'',
    },
    {
        id: 9,
        title: 'Szerviz és karbantartás',
        image:'',
    },
    {
        id: 10,
        title: 'Univerzális kiegészítők',
        image:'',
    },
]

const brands = [
    'alfa-romeo',
    'audi',
    'bmw',
    'citroen',
    'cupra',
    'fiat',
    'ford',
    'honda',
    'hyundai',
    'kia',
    'mazda',
    'mercedes-benz',
    'mini',
    'mitsubishi',
    'nissan',
    'opel',
    'peugeot',
    'porsche',
    'renault',
    'seat',
    'skoda',
    'subaru',
    'suzuki',
    'tesla',
    'toyota',
    'volkswagen',
    'volvo'
]

const formatBrandName = (brand) => {
    return brand
        .split('-')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1))
        .join(' ')
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