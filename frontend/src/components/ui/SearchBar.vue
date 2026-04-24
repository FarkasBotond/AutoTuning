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
                class="h-14 w-full rounded-2xl border border-zinc-200 bg-zinc-100 pl-12 pr-4 text-base text-zinc-700 outline-none transition placeholder:text-zinc-400 focus:border-zinc-300 focus:bg-white">
        </div>
    </form>
</template>