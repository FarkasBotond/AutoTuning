<script setup>
import { useCartStore } from '@stores/cartStore'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

const cartStore = useCartStore()

const handleAddToCart = () =>{
    cartStore.addToCart(props.product)
}
</script>

<template>
    <article
        class="group overflow-hidden rounded-2xl border border-zinc-700 bg-gradient-to-b from-zinc-900 to-black text-white shadow-lg transition duration-300 hover:-translate-y-1 hover:border-zinc-500 hover:shadow-2xl">
        <div class="relative">
            <div
                class="flex h-52 items-center justify-center overflow-hidden bg-zinc-800">
                <img
                    v-if="product.image"
                    :src="product.image"
                    :alt="product.name"
                    class="h-full w-full object-cover transition duration-300 group-hover:scale-105">

                <div
                    v-else
                    class="flex h-full w-full items-center justify-center text-sm font-semibold text-zinc-400">
                    No image
                </div>
            </div>

            <span
                v-if="product.badge"
                class="absolute left-3 top-3 rounded-lg bg-red-600 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white shadow-md">
                {{ product.badge }}
            </span>
        </div>

        <div class="space-y-3 p-4">
            <h3 class="min-h-[56px] text-lg font-bold leading-snug text-white">
                {{ product.name }}
            </h3>

            <p class="text-sm font-medium text-zinc-400">
                {{ product.brand }}
            </p>

            <div class="flex items-end gap-2">
                <span
                    v-if="product.oldPrice"
                    class="text-sm text-zinc-500 line-through">
                    {{ formatPrice(product.oldPrice) }} Ft
                </span>

                <span class="text-2xl font-extrabold text-red-500">
                    {{ formatPrice(product.price) }} Ft
                </span>
            </div>

            <p class="text-sm font-semibold text-green-400">
                {{ product.stockText }}
            </p>

            <button
                type="button"
                class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-500 active:scale-[0.98]">
                Megnézem
            </button>
            <button
                type="button"
                class="w-full rounded-xl bg-red-600 px-4 py-3 text-sm font-bold uppercase tracking-wide text-white transition hover:bg-red-500 active:scale-[0.98]" @click="handleAddToCart">
                Kosárba
            </button>
        </div>
    </article>
</template>