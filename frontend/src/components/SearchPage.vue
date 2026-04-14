<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
</script>

<template>
  <div class="search-page">

    <header class="header">
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

    <main class="main-content">
      <div class="menu">
      </div>

      <section class="search-content">

        <div class="filter">
          <button>Filter</button>
        </div>

        <div class="products">

          <div class="product-card">
          </div>

          <div class="product-card">
          </div>

          <div class="product-card">
          </div>

        </div>

      </section>
    </main>

    <footer class="footer">
      <div class="contact">
        Contact
      </div>
    </footer>

  </div>
</template>