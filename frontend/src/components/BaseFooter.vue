<script setup>

</script>

<template>
    <footer class="mt-14 px-4 pb-8 md:px-6 md:pb-10">
        <div class="glass-panel mx-auto w-full max-w-[1500px] p-6 md:p-8">
            <div class="grid gap-8 lg:grid-cols-[1.3fr_1fr_1fr]">
                <div>
                    <h2 class="text-2xl font-extrabold text-zinc-900">RaceDistrict</h2>
                    <p class="mt-3 max-w-lg text-sm leading-relaxed text-zinc-600">
                        Prémium autótuning alkatrészek, megbízható gyártóktól. Válassz márka szerint,
                        hasonlítsd össze a termékeket, és rendelj biztonságosan.
                    </p>
                </div>

                <div class="space-y-3 text-sm text-zinc-700">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-500">Kapcsolat</h3>
                    <p>
                        <span class="font-semibold text-zinc-900">Email:</span>
                        <a href="mailto:racedistrict@gmail.com"
                            class="ml-2 text-teal-700 hover:text-teal-800">racedistrict@gmail.com</a>
                    </p>
                    <p>
                        <span class="font-semibold text-zinc-900">Telefon:</span>
                        <a href="tel:+3630123456" class="ml-2 text-teal-700 hover:text-teal-800">+36 30 123 456</a>
                    </p>
                </div>

                <div class="space-y-3 text-sm text-zinc-700">
                    <h3 class="text-sm font-bold uppercase tracking-wider text-zinc-500">Elérhetőség</h3>
                    <p>
                        <span class="font-semibold text-zinc-900">Instagram:</span>
                        <a href="https://instagram.com/racedistrict" target="_blank"
                            class="ml-2 text-teal-700 hover:text-teal-800">
                            @racedistrict
                        </a>
                    </p>
                    <p>
                        <span class="font-semibold text-zinc-900">Cím:</span>
                        <span class="ml-2">1111 Budapest, Nemtom utca 1.</span>
                    </p>
                </div>
            </div>

            <div class="mt-8 border-t border-zinc-200 pt-4 text-xs text-zinc-500">
                © {{ new Date().getFullYear() }} RaceDistrict. Minden jog fenntartva.
            </div>
        </div>
    </footer>
</template>