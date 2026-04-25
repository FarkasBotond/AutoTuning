<script setup>
const companies = [
    {
        id: 1,
        name: 'RaceDistrict',
        image: company1Img,
        shortDescription: 'Futómű, kipufogó, styling'
    },
    {
        id: 2,
        name: 'Turbo Works',
        image: company2Img,
        shortDescription: 'Turbo és teljesítménynövelés'
    }
]
</script>


<template>
    <section class="mt-8 rounded-2xl bg-zinc-800 p-4 shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="text-3xl font-bold text-white">Tuningcégek</h2>

            <RouterLink :to="{name: 'companies'}" class="rounded-xl bg-zinc-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-red-600">
                Mutass mindent
            </RouterLink>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5">
            <div v-for="company in companies.slice(0, 5)" :key="company.id" class="overflow-hidden rounded-2xl bg-zinc-700 transition hover:-translate-y-1 hover:shadow-xl">
                <img :src="company.image" alt="cég neve" class="h-40 w-full object-cover">
                
                <div class="p-4">
                    <h3 class="text-lg font-bold text-white">
                        {{ company.name }}
                    </h3>

                    <p class="mt-2 text-sm text-zinc-300">
                        {{ company.shortDescription }}
                    </p>
                </div>
            </div>
        </div>
    </section>
</template>