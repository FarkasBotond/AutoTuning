<script setup="">
import BaseFooter from '@components/BaseFooter.vue';
import { useRouter } from 'vue-router'
import { useCartStore } from '@stores/cartStore';


const router = useRouter()

const cartStore = useCartStore()

const goBack = () => {
    if (window.history.length > 1) {
        router.back()
        return
    }

    router.push('/')
}

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

</script>

<template>
    <div class="min-h-screen flex flex-col">

        <main class="mx-auto w-full max-w-[1450px] flex-1 px-4 py-4 md:px-6">
            <button
                type="button"
                class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900"
                @click="goBack"
            >
                ← Vissza
            </button>

            <h1 class="mb-6 text-3xl font-extrabold text-zinc-900 md:text-4xl">
                Kosár
            </h1>

            <div v-if="cartStore.items.length === 0" class="glass-panel p-10 text-center">
                <h2 class="text-2xl font-bold text-zinc-900">
                    A kosár üres
                </h2>
                <p class="mt-2 text-zinc-600">
                    Még nincs termék a kosárban
                </p>
            </div>

            <div v-else class="grid gap-6 lg:grid-cols-[1fr_340px]">
                <section class="glass-panel p-4 md:p-5">
                    <div v-for="item in cartStore.items" :key="item.id"
                        class="mb-4 rounded-2xl border border-zinc-200 bg-white p-4 last:mb-0">
                        <h2 class="text-xl font-bold text-zinc-900">
                            {{ item.name }}
                        </h2>
                        <p class="text-sm text-zinc-500">
                            {{ item.brand }}
                        </p>

                        <div class="mt-3 flex items-center justify-between">
                            <p class="text-lg font-bold text-teal-700">
                                {{ formatPrice(item.price) }} Ft
                            </p>

                            <div class="flex items-center gap-3">
                                <button type="button" class="rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 font-bold text-zinc-700"
                                    @click="cartStore.decreaseQuantity(item.id)">
                                    -
                                </button>

                                <span class="font-semibold">
                                    {{ item.quantity }}
                                </span>

                                <button type="button" class="rounded-lg border border-zinc-200 bg-zinc-100 px-3 py-1.5 font-bold text-zinc-700"
                                    @click="cartStore.increaseQuantity(item.id)">
                                    +
                                </button>
                            </div>
                        </div>

                        <button type="button" class="mt-4 rounded-lg border border-orange-200 bg-orange-50 px-4 py-2 text-sm font-semibold text-orange-700"
                            @click="cartStore.removeFromCart(item.id)">
                            Törlés
                        </button>
                    </div>
                </section>

                <aside class="glass-panel h-fit p-5">
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

                    <button type="button" class="btn-primary mt-6 w-full">
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
  title: Kosár
</route>