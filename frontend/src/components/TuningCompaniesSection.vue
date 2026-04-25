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
    <section class="rounded-2xl bg-gradient-to-b from-zinc-900 to-zinc-800 p-5 shadow-xl">
        <h2 class="mb-4 text-4xl font-bold text-white">
            Gyártók
        </h2>

        <div class="relative">
            <button type="button" @click="scrollLeft"
                class="absolute left-0 top-1/2 z-10 flex h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full bg-white text-zinc-900 shadow-lg transition hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
            </button>

            <button type="button" @click="scrollRight"
                class="absolute right-0 top-1/2 z-10 flex h-14 w-14 -translate-y-1/2 items-center justify-center rounded-full bg-white text-zinc-900 shadow-lg transition hover:scale-105">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="h-7 w-7">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                </svg>
            </button>

            <div ref="slider" class="scrollbar-hide flex gap-4 overflow-x-auto px-12 py-2">
                <div v-for="company in companies" :key="company.id"
                    class="min-w-[190px] max-w-[190px] overflow-hidden rounded-xl bg-zinc-700/90 transition duration-300 hover:-translate-y-1 hover:bg-zinc-600 hover:shadow-xl">
                    <div v-if="company.image" class="h-[190px] overflow-hidden">
                        <img :src="company.image" :alt="company.name" class="h-full w-full object-cover">
                    </div>

                    <div v-else
                        class="flex h-[190px] items-center justify-center bg-zinc-600 text-lg font-bold text-white">
                        {{ company.name }}
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
                class="rounded-xl bg-zinc-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600 mt-4">
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