<script setup>
import { ref } from 'vue'

import company1Img from '@/assets/images/sparco.png'
import company2Img from '@/assets/images/races.jpg'
import company3Img from '@/assets/images/kn.jpg'
import company4Img from '@/assets/images/omp.jpg'
import company5Img from '@/assets/images/foliatec.jpg'
import company6Img from '@/assets/images/ebc.jpg'
import logo from '@/assets/logo.png'



const companies = [
    {
        id: 1,
        name: 'Sparco',
        image: company1Img,
    },
    {
        id: 2,
        name: 'Races',
        image: company2Img,
    },
    {
        id: 3,
        name: 'K&N',
        image: company3Img,
    },
    {
        id: 4,
        name: 'OMP',
        image: company4Img,
    },
    {
        id: 5,
        name: 'Foliatec',
        image: company5Img,
    },
    {
        id: 6,
        name: 'EBC brakes',
        image: company6Img,
    },
    {
        id: 7,
        name: 'valami',
        image: company6Img,
    },
    {
        id: 8,
        name: 'VMEWÖGWJ',
        image: company6Img,
    },
]

const slider = ref(null)

const scrollLeft = () => {
    slider.value?.scrollBy({
        left: -260,
        behavior: 'smooth',
    })
}

const scrollRight = () => {
    slider.value?.scrollBy({
        left: 260,
        behavior: 'smooth',
    })
}
</script>

<template>
    <section class="min-w-0 rounded-2xl bg-gradient-to-b from-zinc-900 to-zinc-800 p-5 shadow-xl">
        <h2 class="mb-4 text-4xl font-bold text-white">Gyártók</h2>

        <div class="relative min-w-0 overflow-hidden">
            <button type="button" @click="scrollLeft"
                class="absolute left-0 top-1/2 z-10 flex h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full bg-white text-zinc-900 shadow-lg transition hover:scale-105">
                ‹
            </button>

            <button type="button" @click="scrollRight"
                class="absolute right-0 top-1/2 z-10 flex h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full bg-white text-zinc-900 shadow-lg transition hover:scale-105">
                ›
            </button>

            <div ref="slider" class="scrollbar-hide flex gap-4 overflow-x-auto px-12 py-2 scroll-smooth">
                <div v-for="company in companies" :key="company.id"
                    class="shrink-0 min-w-[180px] max-w-[180px] overflow-hidden rounded-xl bg-zinc-700/90">
                    <div class="h-[180px] bg-zinc-600">
                        <img v-if="company.image" :src="company.image" :alt="company.name"
                            class="h-full w-full object-cover">
                        <div v-else class="flex h-full items-center justify-center text-lg font-bold text-white">
                            {{ company.name }}
                        </div>
                    </div>

                    <div class="flex items-center justify-center px-3 py-3">
                        <h3 class="text-center text-xl font-bold text-zinc-200">
                            {{ company.name }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-5 flex justify-center">
            <button type="button"
                class="rounded-xl bg-zinc-700 px-8 py-4 text-lg font-bold text-white shadow-md transition hover:bg-red-600">
                Mutass mindent
            </button>
        </div>
    </section>
</template>

<style scoped>
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}

.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
</style>