<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
import ProductCard from '@components/ProductCard.vue';
import BaseFooter from '@components/BaseFooter.vue';

import allithatofutomuImg from '@/assets/images/allithatofutomu.jpg'
import belsoledImg from '@/assets/images/belsoled.jpg'
import diffuserImg from '@/assets/images/diffuser.jpg'
import downpipeImg from '@/assets/images/downpipe.jpg'
import fekbetetImg from '@/assets/images/fekbetet.jpg'
import fekcsoImg from '@/assets/images/fekcso.webp'
import fektarcsaImg from '@/assets/images/fektarcsa.jpg'
import hatsodobImg from '@/assets/images/hatsodob.jpg'
import intercoolerImg from '@/assets/images/intercooler.jpg'
import kerekdolesImg from '@/assets/images/kerekdoles.jpg'
import kesztyuImg from '@/assets/images/kesztyu.webp'
import kipuvegImg from '@/assets/images/kipuveg.webp'
import kormanyImg from '@/assets/images/kormany.webp'
import kozepdobImg from '@/assets/images/kozepdob.jpg'
import ledizzoImg from '@/assets/images/ledizzo.webp'
import legszuroImg from '@/assets/images/legszuro.jpg'
import obdImg from '@/assets/images/obd.webp'
import olajhutoImg from '@/assets/images/olajhuto.jpg'
import parkszenzorImg from '@/assets/images/parkszenzor.avif'
import pedalszettImg from '@/assets/images/pedalszett.jpg'
import rendszamImg from '@/assets/images/rendszam.webp'
import spoilerImg from '@/assets/images/spoiler.webp'
import szilikoncsoImg from '@/assets/images/szilikoncso.jpg'
import tolatokameraImg from '@/assets/images/tolatokamera.avif'
import turbomeroImg from '@/assets/images/turbomero.jpg'
import ulesImg from '@/assets/images/ules.webp'
import valtogombImg from '@/assets/images/valtogomb.png'
import turboImg from '@/assets/images/turbo.jpg'

const products = [
    {
        id: 1,
        name: 'Állítható sportfutómű Audi A3',
        brand: 'TA Technix',
        image: allithatofutomuImg,
        badge: 'Újdonság',
        oldPrice: 126000,
        price: 103790,
        stockText: 'Raktáron'
    },
    {
        id: 2,
        name: 'Sportülés fekete',
        brand: 'Bride',
        image: ulesImg,
        badge: 'Akció',
        oldPrice: 94670,
        price: 81630,
        stockText: 'Raktáron'
    },
    {
        id: 3,
        name: 'Garrett turbó',
        brand: 'Garrett',
        image: turboImg,
        badge: 'Akció',
        oldPrice: 250000,
        price: 225000,
        stockText: 'Raktáron'
    },
    {
        id: 4,
        name: 'Légszűrő',
        brand: 'K&N',
        image: legszuroImg,
        badge: 'Akció',
        oldPrice: 35000,
        price: 28500,
        stockText: 'Raktáron'
    },
    {
        id: 5,
        name: 'Led fényszóró BMW E46',
        brand: 'Osram',
        image: ledizzoImg,
        badge: 'Új',
        oldPrice: 32000,
        price: 30000,
        stockText: 'Raktáron'
    },
    {
        id: 6,
        name: 'Kipufogóvég',
        brand: 'RaceCarbon',
        image: kipuvegImg,
        badge: 'Akció',
        oldPrice: 15000,
        price: 1000,
        stockText: 'Raktáron'
    },
    {
        id: 7,
        name: 'Sportkesztyű',
        brand: 'Racing',
        image: kesztyuImg,
        badge: 'Akció',
        oldPrice: 5000,
        price: 4500,
        stockText: 'Raktáron'
    },
    {
        id: 8,
        name: '3 küllős kormány',
        brand: 'Momo',
        image: kormanyImg,
        badge: 'Akció',
        oldPrice: 95000,
        price: 82000,
        stockText: 'Raktáron'
    },
    {
        id: 9,
        name: 'Rendszámtáblatartó',
        brand: 'HMN',
        image: rendszamImg,
        badge: 'Akció',
        oldPrice: 6900,
        price: 6700,
        stockText: 'Raktáron'
    },
]
</script>

<template>
    <div class="landing-page">

        <BaseHeadLine />


        <main class="flex gap-6 px-8 py-4">
            <aside class="w-[280px] shrink-0">
                <SideMenu />
            </aside>

            <section class="flex-1 rounded-2xl bg-zinc-100 p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-3">
                    <ProductCard v-for="product in products" :key="product.id" :product="product" />
                </div>
            </section>
        </main>

        <BaseFooter />
    </div>
</template>

<route lang="yaml">
name: landing
meta:
  title: landingpage
</route>