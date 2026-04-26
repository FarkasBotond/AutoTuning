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
        <div class="group relative w-full">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.8"
                stroke="currentColor"
                class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-zinc-400 transition group-focus-within:text-teal-600"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.35-4.35M10.5 18a7.5 7.5 0 1 1 0-15 7.5 7.5 0 0 1 0 15Z" />
            </svg>

            <input
                v-model="search"
                type="text"
                placeholder="Termék, márka vagy kulcsszó..."
                class="h-12 w-full rounded-2xl border border-zinc-200/90 bg-white pl-12 pr-4 text-sm text-zinc-800 shadow-sm outline-none transition placeholder:text-zinc-400 focus:border-teal-500 focus:shadow-[0_0_0_4px_rgba(20,184,166,0.15)]"
            />
        </div>
    </form>
</template>