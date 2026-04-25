<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const search = ref('')

const handleSearch = () => {
    const q = search.value.trim()

    if (!q) {
        return
    }

    router.push({
        path: '/SearchPage',
        query: { q }
    })
}
</script>

<template>
    <form @submit.prevent="handleSearch" class="w-full">
        <div class="relative w-full">

            <input v-model="search" type="text" placeholder="Termék keresése..."
                class="h-14 w-full rounded-2xl border border-zinc-300 bg-zinc-100 pl-12 pr-4 text-base text-zinc-800 shadow-sm outline-none transition duration-200 placeholder:text-zinc-500 focus:border-red-500 focus:bg-white focus:shadow-[0_0_0_4px_rgba(239,68,68,0.15)]" />

        </div>
    </form>
</template>