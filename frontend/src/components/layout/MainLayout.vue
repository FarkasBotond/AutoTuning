<script setup>

</script>

<template>
  <div class="container-fluid">

    <!-- HEADER -->
    <BaseHeader />

    <!-- MAIN -->
    <div class="row">

      <!-- SIDEBAR -->
      <div class="col-2 menu d-flex justify-content-center align-items-center">
        <span class="vertical-text">Menu</span>
      </div>

      <!-- CONTENT -->
      <div class="col-10">
        <slot></slot>
      </div>

    </div>

    <!-- FOOTER -->
    <div class="row footer text-center">
      <div class="col-12">Contact</div>
    </div>

  </div>
</template>

<script setup>
import BaseHeader from './BaseHeader.vue'
</script>