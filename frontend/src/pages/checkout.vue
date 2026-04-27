<script setup>
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'
import BaseFooter from '@components/BaseFooter.vue'
import { useCartStore } from '@stores/cartStore'
import { useAuthStore } from '@stores/authStore'
import { createOrder } from '@/lib/api'

const router = useRouter()
const cartStore = useCartStore()
const authStore = useAuthStore()

const isSubmitting = ref(false)
const errorMessage = ref('')
const successOrder = ref(null)

const countries = [
    'Ausztria',
    'Belgium',
    'Bulgária',
    'Ciprus',
    'Csehország',
    'Dánia',
    'Észtország',
    'Finnország',
    'Franciaország',
    'Görögország',
    'Hollandia',
    'Horvátország',
    'Írország',
    'Lengyelország',
    'Lettország',
    'Litvánia',
    'Luxemburg',
    'Magyarország',
    'Málta',
    'Németország',
    'Olaszország',
    'Portugália',
    'Románia',
    'Spanyolország',
    'Svédország',
    'Szlovákia',
    'Szlovénia'
]

const form = ref({
    full_name: '',
    email: '',
    phone: '',
    delivery_method: 'capital_store',
    country: 'Magyarország',
    city: '',
    postal_code: '',
    street_name: '',
    house_number: '',
    note: '',
    payment_method: 'mobile_pay'
})

const paymentFee = computed(() => {
    return form.value.payment_method === 'cash_on_delivery' ? 1000 : 0
})

const finalTotal = computed(() => {
    return cartStore.totalPrice + paymentFee.value
})

const isHomeDelivery = computed(() => {
    return form.value.delivery_method === 'home_delivery'
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

const goBack = () => {
    router.push('/cart')
}

const validateForm = () => {
    if (!form.value.full_name.trim()) {
        return 'A teljes név megadása kötelező.'
    }

    if (!form.value.email.trim()) {
        return 'Az email cím megadása kötelező.'
    }

    if (!form.value.phone.trim()) {
        return 'A telefonszám megadása kötelező.'
    }

    if (!form.value.country) {
        return 'Az ország kiválasztása kötelező.'
    }

    if (isHomeDelivery.value) {
        if (!form.value.city.trim()) {
            return 'Házhozszállításnál a város megadása kötelező.'
        }

        if (!form.value.postal_code.trim()) {
            return 'Házhozszállításnál az irányítószám megadása kötelező.'
        }

        if (!form.value.street_name.trim()) {
            return 'Házhozszállításnál az utca név megadása kötelező.'
        }

        if (!form.value.house_number.trim()) {
            return 'Házhozszállításnál a házszám megadása kötelező.'
        }
    }

    return ''
}

const submitOrder = async () => {
    errorMessage.value = ''

    if (cartStore.items.length === 0) {
        errorMessage.value = 'A kosár üres, ezért nem lehet rendelést leadni.'
        return
    }

    const validationError = validateForm()

    if (validationError) {
        errorMessage.value = validationError
        return
    }

    isSubmitting.value = true

    try {
        const payload = {
            ...form.value,
            city: isHomeDelivery.value ? form.value.city : null,
            postal_code: isHomeDelivery.value ? form.value.postal_code : null,
            street_name: isHomeDelivery.value ? form.value.street_name : null,
            house_number: isHomeDelivery.value ? form.value.house_number : null,
            items: cartStore.items.map(item => ({
                id: item.id,
                quantity: item.quantity
            }))
        }

        const response = await createOrder(payload, authStore.token || null)
        successOrder.value = response.data
        cartStore.clearCart()
    } catch (error) {
        errorMessage.value = error.message || 'A rendelés leadása nem sikerült.'
    } finally {
        isSubmitting.value = false
    }
}

watch(
    () => form.value.delivery_method,
    (deliveryMethod) => {
        if (deliveryMethod === 'capital_store') {
            form.value.city = ''
            form.value.postal_code = ''
            form.value.street_name = ''
            form.value.house_number = ''
        }
    }
)

onMounted(() => {
    if (cartStore.items.length === 0 && !successOrder.value) {
        router.push('/cart')
    }
})
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <main class="mx-auto w-full max-w-[1450px] flex-1 px-4 py-4 md:px-6">
            <button type="button" class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900"
                @click="goBack">
                ← Vissza a kosárhoz
            </button>

            <div v-if="successOrder" class="glass-panel mx-auto max-w-3xl p-8 text-center">
                <div
                    class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl">
                    ✓
                </div>

                <h1 class="text-3xl font-extrabold text-zinc-900">
                    Sikeres rendelés
                </h1>

                <p class="mt-3 text-zinc-600">
                    A rendelésedet rögzítettük. A rendelési azonosítód:
                    <strong>#{{ successOrder.id }}</strong>
                </p>

                <p class="mt-2 text-zinc-600">
                    Végösszeg: <strong>{{ formatPrice(successOrder.total_price) }} Ft</strong>
                </p>

                <button type="button" class="btn-primary mt-6" @click="router.push('/')">
                    Vissza a főoldalra
                </button>
            </div>

            <template v-else>
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-zinc-900 md:text-4xl">
                        Pénztár
                    </h1>
                    <p class="mt-2 text-sm font-semibold text-orange-700">
                        Fontos: szállítás és üzleti átvétel csak Európán belül érhető el.
                    </p>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1fr_390px]">
                    <form class="glass-panel space-y-6 p-5 md:p-6" @submit.prevent="submitOrder">
                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">
                                Kapcsolattartási adatok
                            </h2>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Teljes név</label>
                                    <input v-model="form.full_name" type="text" class="brand-input"
                                        placeholder="Vezetéknév Keresztnév">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Email</label>
                                    <input v-model="form.email" type="email" class="brand-input"
                                        placeholder="emailcím@gmail.com">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Telefonszám</label>
                                    <input v-model="form.phone" type="tel" class="brand-input"
                                        placeholder="+36 30 123 4567">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Ország</label>
                                    <select v-model="form.country" class="brand-input">
                                        <option v-for="country in countries" :key="country" :value="country">
                                            {{ country }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">
                                Átvételi mód
                            </h2>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label
                                    class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50"
                                    :class="form.delivery_method === 'capital_store' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.delivery_method" type="radio" value="capital_store"
                                        class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Fővárosi üzleti átvétel</span>
                                    <span class="mt-2 block text-sm text-zinc-600">
                                        Európán belül minden ország fővárosában van partnerüzlet, ahol átvehető a
                                        rendelés.
                                    </span>
                                </label>

                                <label
                                    class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50"
                                    :class="form.delivery_method === 'home_delivery' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.delivery_method" type="radio" value="home_delivery"
                                        class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Házhozszállítás</span>
                                    <span class="mt-2 block text-sm text-zinc-600">
                                        A megadott európai címre szállítjuk ki a rendelést.
                                    </span>
                                </label>
                            </div>
                        </section>

                        <section v-if="isHomeDelivery" class="rounded-2xl border border-zinc-200 bg-white p-4">
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">
                                Szállítási cím
                            </h2>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Város</label>
                                    <input v-model="form.city" type="text" class="brand-input"
                                        placeholder="Pl. Budapest">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Irányítószám</label>
                                    <input v-model="form.postal_code" type="text" class="brand-input"
                                        placeholder="Pl. 1111">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Utca név</label>
                                    <input v-model="form.street_name" type="text" class="brand-input"
                                        placeholder="Pl. Példa utca">
                                </div>

                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Házszám</label>
                                    <input v-model="form.house_number" type="text" class="brand-input"
                                        placeholder="Pl. 12/A">
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">
                                Fizetési mód
                            </h2>

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label
                                    class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50"
                                    :class="form.payment_method === 'mobile_pay' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.payment_method" type="radio" value="mobile_pay"
                                        class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Mobilfizetés</span>
                                    <span class="mt-2 block text-sm text-zinc-600">
                                        Apple Pay vagy Google Pay jellegű fizetés. Demo módban nincs valódi tranzakció.
                                    </span>
                                </label>

                                <label
                                    class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50"
                                    :class="form.payment_method === 'cash_on_delivery' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.payment_method" type="radio" value="cash_on_delivery"
                                        class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Utánvét</span>
                                    <span class="mt-2 block text-sm text-zinc-600">
                                        Fizetés átvételkor. Kezelési díj: +1 000 Ft.
                                    </span>
                                </label>
                            </div>
                        </section>

                        <section>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">Megjegyzés</label>
                            <textarea v-model="form.note" class="brand-input min-h-[110px] resize-y"
                                placeholder="Pl. pontosítás a rendeléshez vagy átvételhez"></textarea>
                        </section>

                        <div v-if="errorMessage"
                            class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">
                            {{ errorMessage }}
                        </div>

                        <button type="submit" class="btn-primary w-full" :disabled="isSubmitting">
                            {{ isSubmitting ? 'Rendelés mentése...' : 'Rendelés véglegesítése' }}
                        </button>
                    </form>

                    <aside class="glass-panel h-fit p-5">
                        <h2 class="mb-4 text-xl font-bold text-zinc-900">Rendelés összesítő</h2>

                        <div class="space-y-4">
                            <div v-for="item in cartStore.items" :key="item.id"
                                class="rounded-xl border border-zinc-200 bg-white p-3">
                                <div class="flex justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-zinc-900">{{ item.name }}</p>
                                        <p class="text-sm text-zinc-500">{{ item.brand }} × {{ item.quantity }}</p>
                                    </div>
                                    <p class="shrink-0 font-bold text-teal-700">
                                        {{ formatPrice(item.price * item.quantity) }} Ft
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="mt-5 space-y-3 border-t border-zinc-200 pt-4 text-zinc-700">
                            <div class="flex justify-between">
                                <span>Termékek összesen</span>
                                <span>{{ formatPrice(cartStore.totalPrice) }} Ft</span>
                            </div>

                            <div class="flex justify-between">
                                <span>Utánvét díja</span>
                                <span>{{ formatPrice(paymentFee) }} Ft</span>
                            </div>

                            <div class="flex justify-between text-xl font-extrabold text-zinc-900">
                                <span>Végösszeg</span>
                                <span>{{ formatPrice(finalTotal) }} Ft</span>
                            </div>
                        </div>
                    </aside>
                </div>
            </template>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: checkout
meta:
  title: Pénztár
</route>