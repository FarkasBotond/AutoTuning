<script setup>
import SearchBar from '@/components/ui/SearchBar.vue'
import logo from '@/assets/logo.png'
import BaseHeadLine from '@/components/layout/BaseHeadLine.vue'
import SideMenu from '@components/layout/SideMenu.vue';
</script>

<template>
  <div class="search-page">

      <BaseHeadLine />

    <main class="main-content">
      <SideMenu/>

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

    <BaseFooter/>

  </div>
</template>

<route lang="yaml">
name: search
meta:
  title: Search
</route>