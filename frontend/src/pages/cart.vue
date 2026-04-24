<script setup="">
import BaseFooter from '@components/BaseFooter.vue';
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import { useCartStore } from '@stores/cartStore';


const cartStore = useCartStore()

const formatPrce = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

</script>

<template>
    <div class="min-h-screen bg-zinc-200">

        <BaseHeadLine />


        <main class="mx-auto flex max-w-[1450px] px-4 py-4">
            <h1 class="mb-6 text-3xl font-bold text-zinc-900">
                Kosár
            </h1>

            <div v-if="cartStore.items.length === 0" class="rounded-2xl bg-zinc-100 p-8 text-center shadow-sm">
                <h2 class="text-2xl font-bold text-zinc-900">
                    A kosár üres
                </h2>
                <p class="mt-2 text-zinc-600">
                    Még nincs termék a kosárban
                </p>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-[1fr_320px]">
                <section class="rounded-2xl bg-zinc-100 p-4 shadow-sm">
                    <div v-for="item in cartStore.items" :key="item.id"
                        class="mb-4 rounded-xl border border-zinc-300 bg-white p-4 last:mb-0">
                        <h2 class="text-xl font-bold text-zinc-900">
                            {{ item.name }}
                        </h2>
                        <p class="text-sm text-zinc-500">
                            {{ item.brand }}
                        </p>

                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-lg font-semibold text-red-600">
                                {{ formatPrice(item.price) }} Ft
                            </p>

                            <div class="flex items-center gap-3">
                                <button type="button" class="rounded-lg bg-zinc-300 px-3 py-1 font-bold"
                                    @click="cartStore.decreaseQuantity(item.id)">
                                    -
                                </button>

                                <span class="font-semibold">
                                    {{ item.quantity }}
                                </span>

                                <button type="button" class="rounded-lg bg-zinc-300 px-3 py-1 font-bold"
                                    @click="cartStore.increaseQuantity(item.id)">
                                    +
                                </button>
                            </div>
                        </div>

                        <button type="button" class="mt-4 rounded-lg bg-red-600 px-4 py-2 text-sm font-bold"
                            @click="cartStore.removeFromCart(item.id)">
                            Törlés
                        </button>
                    </div>
                </section>

                <aside class="rounded-2xl bg-zinc-100 p-4 shadow-sm">
                    <h2 class="mb-4 text-xl font-bold text-zinc-900">Összesítő</h2>

                    <div class="space-y-3 text-zinc-700">
                        <div class="flex justify-between">
                            <span>Termékek száma</span>
                            <span>{{ cartStore.totalItems }}</span>
                        </div>

                        <div class="flex justify-between text-lg font-bold text-zinc-900">
                            <span>Végösszeg</span>
                            <span>{{ formatPrice(cartStore.totalPrice) }} Ft</span>
                        </div>
                    </div>

                    <button type="button"
                        class="mt-6 w-full rounded-xl bg-zinc-900 px-4 py-3 font-bold text-white hover:bg-zinc-800">
                        Tovább a pénztárhoz
                    </button>
                </aside>

            </div>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: cart
meta:
  title: cart
</route>