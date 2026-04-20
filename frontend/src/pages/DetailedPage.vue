<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
</script>

<template>
  <div class="detailed-page">

      <BaseHeadLine />

    <main class="main-content">
      <SideMenu/>

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