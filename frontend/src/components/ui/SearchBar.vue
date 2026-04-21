<script setup>
import { ref } from 'vue'

const search = ref('')

const emit = defineEmits(['search'])

const handleSearch = () => {
    emit('search', search.value.trim())
}
</script>

<template>
    <form @submit.prevent="handleSearch" class="w-full">
        <div class="relative w-full">
            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-zinc-400">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="2"
                    stroke="currentColor"
                    class="h-5 w-5"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="m21 21-4.35-4.35m1.85-5.15a7 7 0 1 1m1.85-5.15a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"
                    />
                </svg>
            </span>

            <input
                v-model="search"
                type="text"
                placeholder="Termék keresése..."
                class="h-14 w-full rounded-2xl border border-zinc-200 bg-zinc-100 pl-12 pr-4 text-base text-zinc-700 outline-none transition placeholder:text-zinc-400 focus:border-zinc-300 focus:bg-white"
            >
        </div>
    </form>
</template>