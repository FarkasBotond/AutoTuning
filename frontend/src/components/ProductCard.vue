<script setup>
import { useRouter } from 'vue-router'
import { useCartStore } from '@stores/cartStore'

const props = defineProps({
    product: {
        type: Object,
        required: true
    }
})

const emit = defineEmits(['added-to-cart'])

const router = useRouter()
const cartStore = useCartStore()

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

const goToDetails = () => {
    router.push({
        name: 'detailed',
        query: {
            id: props.product.id
        }
    })
}

const handleAddToCart = () => {
    cartStore.addToCart(props.product)
    emit('added-to-cart', props.product)
}
</script>

<template>
    <article
        class="group overflow-hidden rounded-3xl border border-zinc-200 bg-white text-zinc-900 shadow-[0_18px_40px_-26px_rgba(15,23,42,0.45)] transition duration-300 hover:-translate-y-1 hover:border-teal-200 hover:shadow-[0_24px_50px_-22px_rgba(15,118,110,0.28)]"
    >
        <div class="relative">
            <div class="flex h-52 items-center justify-center overflow-hidden bg-white border-b border-dashed border-emerald-600">
                <img
                    v-if="product.image"
                    :src="product.image"
                    :alt="product.name"
                    class="h-full w-full object-contain object-center p-2 transition duration-300"
                >

                <div
                    v-else
                    class="flex h-full w-full items-center justify-center text-sm font-semibold text-zinc-400"
                >
                    Nincs kép
                </div>
            </div>

            <span
                v-if="product.badge"
                class="absolute left-3 top-3 rounded-lg bg-orange-500 px-3 py-1 text-xs font-bold uppercase tracking-wide text-white shadow-md"
            >
                {{ product.badge }}
            </span>
        </div>

        <div class="space-y-3 p-4">
            <h3 class="min-h-[56px] text-lg font-bold leading-snug text-zinc-900">
                {{ product.name }}
            </h3>

            <p class="text-sm font-medium text-zinc-500">
                {{ product.brand }}
            </p>

            <div class="flex items-end gap-2">
                <span
                    v-if="product.oldPrice"
                    class="text-sm text-zinc-400 line-through"
                >
                    {{ formatPrice(product.oldPrice) }} Ft
                </span>

                <span class="text-2xl font-extrabold text-teal-700">
                    {{ formatPrice(product.price) }} Ft
                </span>
            </div>

            <p class="text-sm font-semibold text-emerald-600">
                {{ product.stockText }}
            </p>

            <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                <button
                    type="button"
                    class="rounded-xl border border-zinc-200 bg-zinc-100 px-4 py-3 text-sm font-semibold text-zinc-700 transition hover:bg-zinc-200 active:scale-[0.98]"
                    @click="goToDetails"
                >
                    Megnézem
                </button>

                <button
                    type="button"
                    class="rounded-xl bg-teal-700 px-4 py-3 text-sm font-semibold text-white transition hover:bg-teal-800 active:scale-[0.98]"
                    @click="handleAddToCart"
                >
                    Kosárba
                </button>
            </div>
        </div>
    </article>
</template>