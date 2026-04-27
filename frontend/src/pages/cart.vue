<script setup>
import { useRouter } from 'vue-router'
import BaseFooter from '@components/BaseFooter.vue'
import { useCartStore } from '@stores/cartStore'

const router = useRouter()
const cartStore = useCartStore()

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

const goBack = () => {
    router.push('/')
}

const goCheckout = () => {
    if (cartStore.items.length === 0) {
        return
    }

    router.push('/checkout')
}
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <main class="mx-auto w-full max-w-[1450px] flex-1 px-4 py-4 md:px-6">
            <button type="button" class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900"
                @click="goBack">
                ← Vissza
            </button>

            <h1 class="mb-6 text-3xl font-extrabold text-zinc-900 md:text-4xl">
                Kosár
            </h1>

            <div v-if="cartStore.items.length === 0" class="glass-panel p-10 text-center">
                <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-zinc-100 text-4xl">
                    🛒
                </div>

                <h2 class="text-2xl font-extrabold text-zinc-900">
                    A kosár üres
                </h2>

                <p class="mt-3 text-zinc-600">
                    Még nincs termék a kosárban
                </p>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-[1fr_360px]">
                <section class="glass-panel space-y-4 p-5 md:p-6">
                    <div v-if="cartStore.lastError" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                        {{ cartStore.lastError }}
                    </div>
                    <article v-for="item in cartStore.items" :key="item.id"
                        class="rounded-2xl border border-zinc-200 bg-white p-4">
                        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                            <div class="flex gap-4">
                                <img v-if="item.image" :src="item.image" :alt="item.name"
                                    class="h-24 w-24 rounded-xl border border-zinc-200 object-contain p-2">

                                <div>
                                    <h2 class="text-xl font-bold text-zinc-900">
                                        {{ item.name }}
                                    </h2>

                                    <p class="mt-1 text-sm font-medium text-zinc-500">
                                        {{ item.brand }}
                                    </p>

                                    <p class="mt-4 text-xl font-extrabold text-teal-700">
                                        {{ formatPrice(item.price) }} Ft
                                    </p>

                                    <button type="button"
                                        class="mt-4 rounded-xl border border-orange-200 bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700 transition hover:bg-orange-100"
                                        @click="cartStore.removeFromCart(item.id)">
                                        Törlés
                                    </button>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <button type="button"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-200 bg-zinc-100 text-lg font-bold text-zinc-700 transition hover:bg-zinc-200"
                                    @click="cartStore.decreaseQuantity(item.id)">
                                    -
                                </button>

                                <span class="min-w-8 text-center font-bold text-zinc-900">
                                    {{ item.quantity }}
                                </span>

                                <button type="button"
                                    class="flex h-10 w-10 items-center justify-center rounded-xl border border-zinc-200 bg-zinc-100 text-lg font-bold text-zinc-700 transition hover:bg-zinc-200"
                                    @click="cartStore.increaseQuantity(item.id)">
                                    +
                                </button>
                            </div>
                        </div>
                    </article>
                </section>

                <aside class="glass-panel h-fit p-5">
                    <h2 class="mb-5 text-xl font-bold text-zinc-900">
                        Összesítő
                    </h2>

                    <div class="space-y-4">
                        <div class="flex justify-between text-zinc-700">
                            <span>Termékek száma</span>
                            <span>{{ cartStore.totalItems }}</span>
                        </div>

                        <div class="flex justify-between text-xl font-extrabold text-zinc-900">
                            <span>Végösszeg</span>
                            <span>{{ formatPrice(cartStore.totalPrice) }} Ft</span>
                        </div>

                        <button type="button" class="btn-primary w-full" @click="goCheckout">
                            Tovább a pénztárhoz
                        </button>
                    </div>
                </aside>
            </div>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: cart
meta:
  title: Kosár
</route>
