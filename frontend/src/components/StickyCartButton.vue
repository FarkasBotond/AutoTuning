<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useCartStore } from '@stores/cartStore'

const route = useRoute()
const router = useRouter()
const cartStore = useCartStore()

const shouldShow = computed(() => {
    return route.path !== '/cart' && cartStore.totalItems > 0
})

const openCart = () => {
    router.push('/cart')
}
</script>

<template>
    <button
        v-if="shouldShow"
        type="button"
        class="fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl bg-orange-500 px-5 py-4 text-sm font-extrabold text-white shadow-2xl transition hover:-translate-y-0.5 hover:bg-orange-600"
        @click="openCart"
    >
        <span class="text-xl">🛒</span>
        <span>Kosár</span>
        <span class="rounded-full bg-white px-2 py-1 text-xs font-extrabold text-orange-600">
            {{ cartStore.totalItems }}
        </span>
    </button>
</template>
