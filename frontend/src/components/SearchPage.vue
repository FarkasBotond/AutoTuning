<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
</script>

<template>
  <div class="search-page">

      <BaseHeadLine />

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