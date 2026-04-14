<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
</script>

<template>
  <header class="w-full bg-white border-b border-gray-200">
    <div class="mx-auto flex w-full max-w-7xl items-center gap-4 px-4 py-3 md:px-6">
      <div class="shrink-0">
        <img :src="logo" alt="Logo" class="h-12 w-auto object-contain" />
      </div>

      <div class="min-w-0 flex-1">
        <SearchBar />
      </div>

      <div class="flex shrink-0 items-center gap-2">
        <button
          type="button"
          class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200">
          Profile
        </button>

        <button
          type="button"
          class="rounded-md bg-gray-100 px-4 py-2 text-sm font-medium hover:bg-gray-200">
          Cart
        </button>
      </div>
    </div>
  </header>
</template>