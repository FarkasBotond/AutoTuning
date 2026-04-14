<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
</script>

<template>
  <div class="detailed-page">

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

      <section class="product-content">
        <div class="top-content">
          <div class="product-image">
            <img src="" alt="Product image">
          </div>

          <div class="product-details">

          </div>
        </div>

        <div class="related-products">

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