<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
</script>

<template>
    <div class="landing-page">
        <header>
            <div class="logo">
                <img :src="logo" alt="Logó">
            </div>
            <div class="search">
                <SearchBar />
            </div>

            <div class="user-section">
                <div class="profile">
                    <button>Profile</button>
                </div>

                <div class="cart">
                    <button>Cart</button>
                </div>
            </div>

        </header>

        <main>
            <div class="menu">

            </div>

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
