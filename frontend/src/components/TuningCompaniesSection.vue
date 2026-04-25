<script setup>
const companies = [
    {
        id: 1,
        name: 'RaceDistrict',
        shortDescription: 'Futómű, kipufogó, styling'
    },
    {
        id: 2,
        name: 'Turbo Works',
        shortDescription: 'Turbó és teljesítménynövelés'
    }
]
</script>

<template>
    <section class="rounded-2xl bg-zinc-800 p-4 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-3xl font-bold text-white">Tuningcégek</h2>

            <button type="button"
                class="rounded-xl bg-zinc-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                Mutass mindent
            </button>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div v-for="company in companies" :key="company.id" class="rounded-2xl bg-zinc-700 p-4 text-white">
                <h3 class="text-lg font-bold">{{ company.name }}</h3>
                <p class="mt-2 text-sm text-zinc-300">{{ company.shortDescription }}</p>
            </div>
        </div>
    </section>
</template>