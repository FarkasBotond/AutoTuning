<script setup>
import { RouterLink } from 'vue-router'

import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import BaseFooter from '@components/BaseFooter.vue'

import company1Img from '@/assets/images/sparco.png'
import company2Img from '@/assets/images/races.jpg'
import company3Img from '@/assets/images/kn.jpg'
import company4Img from '@/assets/images/omp.jpg'
import company5Img from '@/assets/images/foliatec.jpg'
import company6Img from '@/assets/images/ebc.jpg'

const companies = [
    {
        id: 1,
        name: 'Sparco',
        image: company1Img,
        description: 'Versenyfelszerelések, ülések, kormányok és sportos kiegészítők.',
        website: 'https://www.sparco-official.com'
    },
    {
        id: 2,
        name: 'Races',
        image: company2Img,
        description: 'Tuning alkatrészek és teljesítménynövelő kiegészítők.',
        website: 'https://race-shop.hu'
    },
    {
        id: 3,
        name: 'K&N',
        image: company3Img,
        description: 'Sportlégszűrők, szívórendszerek és teljesítményorientált megoldások.',
        website: 'https://www.knfilters.com'
    },
    {
        id: 4,
        name: 'OMP',
        image: company4Img,
        description: 'Versenyülések, biztonsági felszerelések és motorsport kiegészítők.',
        website: 'https://www.ompracing.com'
    },
    {
        id: 5,
        name: 'Foliatec',
        image: company5Img,
        description: 'Fóliák, féknyeregfestékek és külső-belső autós kiegészítők.',
        website: 'https://www.foliatec.com'
    },
    {
        id: 6,
        name: 'EBC Brakes',
        image: company6Img,
        description: 'Féktárcsák, fékbetétek és nagy teljesítményű fékrendszer alkatrészek.',
        website: 'https://www.ebcbrakes.com'
    }
]
</script>

<template>
    <div class="min-h-screen overflow-x-hidden">
        <BaseHeadLine />

        <main class="mx-auto flex w-full max-w-[1550px] flex-col gap-6 px-4 py-4 md:px-6 lg:flex-row lg:items-start">
            <aside class="w-full shrink-0 lg:sticky lg:top-6 lg:w-[295px]">
                <SideMenu />
            </aside>

            <section class="flex-1 min-w-0 space-y-5 pb-14">
                <div class="glass-panel p-5 md:p-6">
                    <div class="mb-6 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                        <div>
                            <h1 class="section-title">
                                Tuning cégek
                            </h1>

                            <p class="mt-2 text-sm font-medium text-zinc-500">
                                Böngéssz a tuning cégek között, nyisd meg az adatlapjukat vagy látogasd meg a
                                weboldalukat.
                            </p>
                        </div>

                        <RouterLink to="/" class="btn-muted px-4 py-2 text-xs md:text-sm">
                            Vissza
                        </RouterLink>
                    </div>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        <article v-for="company in companies" :key="company.id"
                            class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <RouterLink :to="`/brand/${company.id}`" class="block">
                                <div class="flex h-44 items-center justify-center bg-zinc-100 p-4">
                                    <img :src="company.image" :alt="company.name"
                                        class="h-full w-full object-contain transition duration-300 group-hover:scale-105">
                                </div>

                                <div class="space-y-2 p-4">
                                    <h2 class="line-clamp-1 text-center text-lg font-black text-zinc-900">
                                        {{ company.name }}
                                    </h2>

                                    <p class="line-clamp-2 text-center text-sm font-medium text-zinc-500">
                                        {{ company.description }}
                                    </p>
                                </div>
                            </RouterLink>

                            <div class="flex gap-2 border-t border-zinc-100 p-4">
                                <RouterLink :to="`/brand/${company.id}`"
                                    class="flex-1 rounded-xl bg-zinc-950 px-4 py-2 text-center text-sm font-bold text-white transition hover:bg-zinc-800">
                                    Adatlap
                                </RouterLink>

                                <a :href="company.website" target="_blank" rel="noopener noreferrer"
                                    class="flex-1 rounded-xl border border-zinc-200 bg-white px-4 py-2 text-center text-sm font-bold text-zinc-800 transition hover:bg-zinc-50">
                                    Weboldal
                                </a>
                            </div>
                        </article>
                    </div>
                </div>
            </section>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: companies
meta:
  title: Tuning cégek
</route>