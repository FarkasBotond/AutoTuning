<script setup>
import { computed, onMounted } from 'vue'
import { useRoute, RouterLink } from 'vue-router'

import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue'
import BaseFooter from '@components/BaseFooter.vue'
import { useTuningCompanyStore } from '@stores/tuningCompanyStore'

const route = useRoute()
const tuningCompanyStore = useTuningCompanyStore()

const company = computed(() => tuningCompanyStore.selectedCompany)

const websiteUrl = computed(() => {
    return company.value?.websiteUrl ?? null
})

onMounted(async () => {
    try {
        await tuningCompanyStore.fetchCompanyById(route.params.id)
    } catch (error) {
        console.error('Nem sikerült betölteni a tuning céget:', error)
    }
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
                <div v-if="tuningCompanyStore.isLoading"
                    class="glass-panel p-8 text-center font-semibold text-zinc-600">
                    Betöltés...
                </div>

                <div v-else-if="tuningCompanyStore.error"
                    class="rounded-2xl bg-red-50 p-8 text-center font-semibold text-red-700 shadow-sm">
                    {{ tuningCompanyStore.error }}
                </div>

                <div v-else-if="!company" class="glass-panel p-8 text-center font-semibold text-zinc-600">
                    A tuning cég nem található.
                </div>

                <div v-else class="glass-panel overflow-hidden p-5 md:p-6">
                    <div class="mb-6 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                        <div>
                            <p class="text-sm font-bold uppercase tracking-wide text-teal-700">
                                Tuning cég adatlap
                            </p>

                            <h1 class="mt-2 text-4xl font-black text-zinc-950">
                                {{ company.name }}
                            </h1>
                        </div>

                        <RouterLink :to="{ name: 'companies' }" class="btn-muted px-4 py-2 text-xs md:text-sm">
                            Vissza a cégekhez
                        </RouterLink>
                    </div>

                    <div class="grid gap-6 lg:grid-cols-[360px_1fr]">
                        <div class="flex min-h-[280px] items-center justify-center rounded-3xl bg-zinc-100 p-6">
                            <div
                                class="flex h-full min-h-[220px] w-full items-center justify-center rounded-2xl bg-zinc-200 px-6 text-center text-3xl font-black text-zinc-600">
                                {{ company.name }}
                            </div>
                        </div>

                        <div
                            class="flex flex-col justify-center rounded-3xl border border-zinc-200 bg-white p-6 shadow-sm">
                            <h2 class="text-2xl font-black text-zinc-950">
                                Cégleírás
                            </h2>

                            <p v-if="company.description" class="mt-4 text-base font-medium leading-7 text-zinc-600">
                                {{ company.description }}
                            </p>

                            <p v-else class="mt-4 text-base font-medium leading-7 text-zinc-500">
                                Ehhez a tuning céghez még nincs részletes leírás megadva.
                            </p>

                            <div class="mt-8 flex flex-wrap gap-3">
                                <a v-if="websiteUrl" :href="websiteUrl" target="_blank" rel="noopener noreferrer"
                                    class="rounded-xl bg-teal-700 px-5 py-3 text-sm font-black text-white transition hover:bg-teal-800">
                                    Külső weboldal megnyitása
                                </a>

                                <RouterLink :to="{ name: 'companies' }"
                                    class="rounded-xl border border-zinc-200 bg-white px-5 py-3 text-sm font-black text-zinc-800 transition hover:bg-zinc-50">
                                    Összes tuning cég
                                </RouterLink>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: company-detail
meta:
  title: Tuning cég adatlap
</route>