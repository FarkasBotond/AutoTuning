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

</script>

<template>
    <aside class="w-full rounded-xl bg-gradient-to-b from-zinc-700 to-zinc-900 p-4 box-border">
        <div class="mb-4 grid grid-cols-2 items-center border-b border-white/20">
            <button
                @click="activeTab = 'categories'"
                class="relative px-2 py-4 text-center text-xl font-bold uppercase tracking-wide text-white transition"
                :class="activeTab === 'categories' ? 'after:absolute after:left-[15%] after:right-[15%] after:bottom-0 after:h-[3px] after:rounded-full after:bg-white' : ''">
            
                Kategóriák
            </button>

            <button
                @click="activeTab = 'brands'"
                class="relative border-l border-white/20 px-2 py-4 text-center text-xl font-bold uppercase tracking-wide text-white transition"
                :class="activeTab === 'brands' ? 'after:absolute after:left-[15%] after:right-[15%] after:bottom-0 after:h-[3px] after:rounded-full after:bg-white' : ''">
            
                Gyártók
            </button>
        </div>

        <div v-if="activeTab === 'categories'" class="flex flex-col gap-3">
            <div
                v-for="item in categories"
                :key="item.id"
                class="relative h-[72px] cursor-pointer overflow-hidden rounded-xl bg-cover bg-center"
                :style="{ backgroundImage: `url(${item.image})` }">
            
                <div class="absolute inset-0 bg-black/50"></div>

                <span class="absolute left-4 top-1/2 z-10 -translate-y-1/2 text-2xl font-bold text-white">
                    {{ item.title }}
                </span>
            </div>
        </div>

        <div v-else class="flex flex-col gap-3">
            <div
                v-for="brand in brands"
                :key="brand"
                class="cursor-pointer rounded-xl bg-white/10 px-4 py-4 text-lg font-semibold text-white transition hover:bg-white/20">
            
                {{ brand }}
            </div>
        </div>
    </aside>
</template>