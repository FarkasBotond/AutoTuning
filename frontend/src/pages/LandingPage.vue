<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
</script>

<template>
    <div class="landing-page">

            <BaseHeadLine/>


        <main>
            <SideMenu/>

            <section class="products">

            </section>
        </main>

        <footer>
            <div class="contact">
                Contact
            </div>

        </footer>
    </div>
</template>

<route lang="yaml">
name: landing
meta:
<<<<<<< HEAD
  title: Landing
=======
  title: Landing Page
>>>>>>> 00e9019 (Vue files (admin login and admin panel/page for changes))
</route>