<script setup>
import { onMounted, computed } from 'vue'
import { RouterLink } from 'vue-router'

import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import BaseFooter from '@components/BaseFooter.vue'
import { useTuningCompanyStore } from '@stores/tuningCompanyStore'

const tuningCompanyStore = useTuningCompanyStore()

const companies = computed(() => tuningCompanyStore.companies)

const getCompanyWebsite = (company) => {
    return company.website_url ?? company.website ?? company.url ?? null
}

onMounted(async () => {
    await tuningCompanyStore.fetchAllCompanies()
})
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

                    <div v-if="tuningCompanyStore.isLoading"
                        class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
                        Betöltés...
                    </div>

                    <div v-else-if="tuningCompanyStore.error"
                        class="rounded-2xl bg-red-50 p-8 text-center font-semibold text-red-700 shadow-sm">
                        {{ tuningCompanyStore.error }}
                    </div>

                    <div v-else-if="companies.length === 0"
                        class="rounded-2xl bg-white p-8 text-center font-semibold text-zinc-600 shadow-sm">
                        Nincs megjeleníthető tuning cég.
                    </div>

                    <div v-else class="grid grid-cols-1 gap-5 sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4">
                        <article v-for="company in companies" :key="company.id"
                            class="group overflow-hidden rounded-2xl border border-zinc-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">
                            <RouterLink :to="`/company/${company.id}`" class="block">
                                <div class="flex h-44 items-center justify-center bg-zinc-100 p-4">
                                    <div
                                        class="flex h-full w-full items-center justify-center rounded-xl bg-zinc-200 px-4 text-center text-2xl font-black text-zinc-600">
                                        {{ company.name }}
                                    </div>
                                </div>

                                <div class="space-y-2 p-4">
                                    <h2 class="line-clamp-1 text-center text-lg font-black text-zinc-900">
                                        {{ company.name }}
                                    </h2>

                                    <p v-if="company.description"
                                        class="line-clamp-2 text-center text-sm font-medium text-zinc-500">
                                        {{ company.description }}
                                    </p>
                                </div>
                            </RouterLink>

                            <div class="flex gap-2 border-t border-zinc-100 p-4">
                                <RouterLink :to="`/company/${company.id}`"
                                    class="flex-1 rounded-xl bg-zinc-950 px-4 py-2 text-center text-sm font-bold text-white transition hover:bg-zinc-800">
                                    Adatlap
                                </RouterLink>

                                <a v-if="getCompanyWebsite(company)" :href="getCompanyWebsite(company)" target="_blank"
                                    rel="noopener noreferrer"
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