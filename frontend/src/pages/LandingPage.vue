<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
</script>

<template>
    <div class="landing-page">
        <header>
            <div class="logo">
                <img>
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
