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
const useShippingAsBilling = ref(true)

const countries = [
    'Ausztria', 'Belgium', 'Bulgária', 'Ciprus', 'Csehország', 'Dánia', 'Észtország',
    'Finnország', 'Franciaország', 'Görögország', 'Hollandia', 'Horvátország', 'Írország',
    'Lengyelország', 'Lettország', 'Litvánia', 'Luxemburg', 'Magyarország', 'Málta',
    'Németország', 'Olaszország', 'Portugália', 'Románia', 'Spanyolország', 'Svédország',
    'Szlovákia', 'Szlovénia'
]

const form = ref({
    first_name: '',
    last_name: '',
    email: '',
    phone_local: '',
    delivery_method: 'capital_store',
    country: 'Magyarország',
    city: '',
    postal_code: '',
    street_name: '',
    house_number: '',
    billing_country: 'Magyarország',
    billing_city: '',
    billing_postal_code: '',
    billing_street_name: '',
    billing_house_number: '',
    note: '',
    payment_method: 'mobile_pay'
})

const isAuthenticated = computed(() => authStore.isAuthenticated)

const paymentFee = computed(() => {
    return form.value.payment_method === 'cash_on_delivery' ? 1000 : 0
})

const finalTotal = computed(() => {
    return cartStore.totalPrice + paymentFee.value
})

const isHomeDelivery = computed(() => {
    return form.value.delivery_method === 'home_delivery'
})

const phoneNumber = computed(() => {
    return `+36${String(form.value.phone_local || '').replace(/\D/g, '')}`
})

const formatPrice = (price) => {
    return new Intl.NumberFormat('hu-HU').format(price)
}

const goBack = () => {
    router.push('/cart')
}

const cleanDigits = () => {
    form.value.phone_local = String(form.value.phone_local || '').replace(/\D/g, '').slice(0, 9)
}

const copyShippingToBilling = () => {
    if (!isHomeDelivery.value || !useShippingAsBilling.value) {
        return
    }

    form.value.billing_country = form.value.country
    form.value.billing_city = form.value.city
    form.value.billing_postal_code = form.value.postal_code
    form.value.billing_street_name = form.value.street_name
    form.value.billing_house_number = form.value.house_number
}

const validateForm = () => {
    if (form.value.first_name.trim().length < 3) {
        return 'A keresztnév legalább 3 karakter legyen.'
    }

    if (form.value.last_name.trim().length < 3) {
        return 'A vezetéknév legalább 3 karakter legyen.'
    }

    if (!form.value.email.trim() || !form.value.email.includes('@')) {
        return 'Érvényes email címet adj meg.'
    }

    if (!/^\d{8,9}$/.test(form.value.phone_local)) {
        return 'A telefonszám +36 után 8 vagy 9 számjegyből álljon.'
    }

    if (isHomeDelivery.value) {
        if (!form.value.city.trim() || !form.value.postal_code.trim() || !form.value.street_name.trim() || !form.value.house_number.trim()) {
            return 'Házhozszállításnál minden szállítási címet ki kell tölteni.'
        }
    }

    if (!form.value.billing_country.trim() || !form.value.billing_city.trim() || !form.value.billing_postal_code.trim() || !form.value.billing_street_name.trim() || !form.value.billing_house_number.trim()) {
        return 'A számlázási címet kötelező kitölteni.'
    }

    if (form.value.note.length > 150) {
        return 'A megjegyzés legfeljebb 150 karakter lehet.'
    }

    return ''
}

const submitOrder = async () => {
    errorMessage.value = ''
    cleanDigits()
    copyShippingToBilling()

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
            first_name: form.value.first_name.trim(),
            last_name: form.value.last_name.trim(),
            email: isAuthenticated.value ? authStore.user.email : form.value.email.trim(),
            phone: phoneNumber.value,
            delivery_method: form.value.delivery_method,
            country: form.value.country,
            city: isHomeDelivery.value ? form.value.city.trim() : null,
            postal_code: isHomeDelivery.value ? form.value.postal_code.trim() : null,
            street_name: isHomeDelivery.value ? form.value.street_name.trim() : null,
            house_number: isHomeDelivery.value ? form.value.house_number.trim() : null,
            billing_country: form.value.billing_country.trim(),
            billing_city: form.value.billing_city.trim(),
            billing_postal_code: form.value.billing_postal_code.trim(),
            billing_street_name: form.value.billing_street_name.trim(),
            billing_house_number: form.value.billing_house_number.trim(),
            note: form.value.note.trim() || null,
            payment_method: form.value.payment_method,
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
            useShippingAsBilling.value = false
        }
    }
)

watch(
    () => [form.value.country, form.value.city, form.value.postal_code, form.value.street_name, form.value.house_number, useShippingAsBilling.value],
    copyShippingToBilling
)

onMounted(() => {
    if (authStore.isAuthenticated) {
        form.value.email = authStore.user?.email || ''
    }

    if (cartStore.items.length === 0 && !successOrder.value) {
        router.push('/cart')
    }
})
</script>

<template>
    <div class="min-h-screen flex flex-col">
        <main class="mx-auto w-full max-w-[1450px] flex-1 px-4 py-4 md:px-6">
            <button type="button" class="mb-4 text-sm font-semibold text-zinc-600 transition-colors hover:text-zinc-900" @click="goBack">
                ← Vissza a kosárhoz
            </button>

            <div v-if="successOrder" class="glass-panel mx-auto max-w-3xl p-8 text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100 text-3xl">✓</div>
                <h1 class="text-3xl font-extrabold text-zinc-900">Sikeres rendelés</h1>
                <p class="mt-3 text-zinc-600">A rendelésedet rögzítettük. A rendelési azonosítód: <strong>#{{ successOrder.id }}</strong></p>
                <p class="mt-2 text-zinc-600">Végösszeg: <strong>{{ formatPrice(successOrder.total_price) }} Ft</strong></p>
                <button type="button" class="btn-primary mt-6" @click="router.push('/')">Vissza a főoldalra</button>
            </div>

            <template v-else>
                <div class="mb-6">
                    <h1 class="text-3xl font-extrabold text-zinc-900 md:text-4xl">Pénztár</h1>
                    <p class="mt-2 text-sm font-semibold text-orange-700">Fontos: szállítás és üzleti átvétel csak Európán belül érhető el.</p>
                </div>

                <div class="grid gap-6 lg:grid-cols-[1fr_390px]">
                    <form class="glass-panel space-y-6 p-5 md:p-6" @submit.prevent="submitOrder">
                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">Kapcsolattartási adatok</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Vezetéknév</label>
                                    <input v-model="form.last_name" type="text" class="brand-input" placeholder="Pl. Kovács">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Keresztnév</label>
                                    <input v-model="form.first_name" type="text" class="brand-input" placeholder="Pl. Péter">
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Email</label>
                                    <input v-model="form.email" type="email" class="brand-input" placeholder="emailcím@gmail.com" :disabled="isAuthenticated">
                                    <p v-if="isAuthenticated" class="mt-1 text-xs font-semibold text-zinc-500">Bejelentkezett felhasználónál az email cím nem módosítható.</p>
                                </div>
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Telefonszám</label>
                                    <div class="flex">
                                        <span class="inline-flex items-center rounded-l-xl border border-r-0 border-zinc-200 bg-zinc-100 px-4 text-sm font-bold text-zinc-700">+36</span>
                                        <input v-model="form.phone_local" type="text" inputmode="numeric" class="brand-input rounded-l-none" placeholder="301234567" @input="cleanDigits">
                                    </div>
                                </div>
                            </div>
                        </section>

                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">Átvételi mód</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50" :class="form.delivery_method === 'capital_store' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.delivery_method" type="radio" value="capital_store" class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Fővárosi üzleti átvétel</span>
                                    <span class="mt-2 block text-sm text-zinc-600">Európán belül minden ország fővárosában van partnerüzlet.</span>
                                </label>
                                <label class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50" :class="form.delivery_method === 'home_delivery' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.delivery_method" type="radio" value="home_delivery" class="sr-only">
                                    <span class="block text-lg font-bold text-zinc-900">Házhozszállítás</span>
                                    <span class="mt-2 block text-sm text-zinc-600">A megadott európai címre szállítjuk ki a rendelést.</span>
                                </label>
                            </div>
                        </section>

                        <section v-if="isHomeDelivery" class="rounded-2xl border border-zinc-200 bg-white p-4">
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">Szállítási cím</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Ország</label>
                                    <select v-model="form.country" class="brand-input"><option v-for="country in countries" :key="country" :value="country">{{ country }}</option></select>
                                </div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Város</label><input v-model="form.city" type="text" class="brand-input" placeholder="Pl. Budapest"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Irányítószám</label><input v-model="form.postal_code" type="text" class="brand-input" placeholder="Pl. 1111"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Utca név</label><input v-model="form.street_name" type="text" class="brand-input" placeholder="Pl. Példa utca"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Házszám</label><input v-model="form.house_number" type="text" class="brand-input" placeholder="Pl. 12/A"></div>
                            </div>
                        </section>

                        <section class="rounded-2xl border border-zinc-200 bg-white p-4">
                            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                                <h2 class="text-2xl font-bold text-zinc-900">Számlázási cím</h2>
                                <label v-if="isHomeDelivery" class="inline-flex items-center gap-2 text-sm font-semibold text-zinc-700">
                                    <input v-model="useShippingAsBilling" type="checkbox" class="h-4 w-4 accent-teal-700">
                                    Megegyezik a szállítási címmel
                                </label>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label class="mb-2 block text-sm font-semibold text-zinc-700">Ország</label>
                                    <select v-model="form.billing_country" class="brand-input" :disabled="isHomeDelivery && useShippingAsBilling"><option v-for="country in countries" :key="country" :value="country">{{ country }}</option></select>
                                </div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Város</label><input v-model="form.billing_city" type="text" class="brand-input" :disabled="isHomeDelivery && useShippingAsBilling" placeholder="Pl. Budapest"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Irányítószám</label><input v-model="form.billing_postal_code" type="text" class="brand-input" :disabled="isHomeDelivery && useShippingAsBilling" placeholder="Pl. 1111"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Utca név</label><input v-model="form.billing_street_name" type="text" class="brand-input" :disabled="isHomeDelivery && useShippingAsBilling" placeholder="Pl. Példa utca"></div>
                                <div><label class="mb-2 block text-sm font-semibold text-zinc-700">Házszám</label><input v-model="form.billing_house_number" type="text" class="brand-input" :disabled="isHomeDelivery && useShippingAsBilling" placeholder="Pl. 12/A"></div>
                            </div>
                        </section>

                        <section>
                            <h2 class="mb-4 text-2xl font-bold text-zinc-900">Fizetési mód</h2>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <label class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50" :class="form.payment_method === 'mobile_pay' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.payment_method" type="radio" value="mobile_pay" class="sr-only"><span class="block text-lg font-bold text-zinc-900">Mobilfizetés</span><span class="mt-2 block text-sm text-zinc-600">Apple Pay vagy Google Pay jellegű demo fizetés.</span>
                                </label>
                                <label class="cursor-pointer rounded-2xl border bg-white p-4 transition hover:border-teal-300 hover:bg-teal-50" :class="form.payment_method === 'cash_on_delivery' ? 'border-teal-400 bg-teal-50' : 'border-zinc-200'">
                                    <input v-model="form.payment_method" type="radio" value="cash_on_delivery" class="sr-only"><span class="block text-lg font-bold text-zinc-900">Utánvét</span><span class="mt-2 block text-sm text-zinc-600">Fizetés átvételkor. Kezelési díj: +1 000 Ft.</span>
                                </label>
                            </div>
                        </section>

                        <section>
                            <label class="mb-2 block text-sm font-semibold text-zinc-700">Megjegyzés</label>
                            <textarea v-model="form.note" maxlength="150" class="brand-input min-h-[110px] resize-y" placeholder="Maximum 150 karakter"></textarea>
                            <p class="mt-1 text-xs font-semibold text-zinc-500">{{ form.note.length }}/150 karakter</p>
                        </section>

                        <div v-if="errorMessage" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-semibold text-red-700">{{ errorMessage }}</div>
                        <button type="submit" class="btn-primary w-full" :disabled="isSubmitting">{{ isSubmitting ? 'Rendelés mentése...' : 'Rendelés véglegesítése' }}</button>
                    </form>

                    <aside class="glass-panel h-fit p-5">
                        <h2 class="mb-4 text-xl font-bold text-zinc-900">Rendelés összesítő</h2>
                        <div class="space-y-4">
                            <div v-for="item in cartStore.items" :key="item.id" class="rounded-xl border border-zinc-200 bg-white p-3">
                                <div class="flex justify-between gap-3"><div><p class="font-bold text-zinc-900">{{ item.name }}</p><p class="text-sm text-zinc-500">{{ item.brand }} × {{ item.quantity }}</p></div><p class="shrink-0 font-bold text-teal-700">{{ formatPrice(item.price * item.quantity) }} Ft</p></div>
                            </div>
                        </div>
                        <div class="mt-5 space-y-3 border-t border-zinc-200 pt-4 text-zinc-700"><div class="flex justify-between"><span>Termékek összesen</span><span>{{ formatPrice(cartStore.totalPrice) }} Ft</span></div><div class="flex justify-between"><span>Utánvét díja</span><span>{{ formatPrice(paymentFee) }} Ft</span></div><div class="flex justify-between text-xl font-extrabold text-zinc-900"><span>Végösszeg</span><span>{{ formatPrice(finalTotal) }} Ft</span></div></div>
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
