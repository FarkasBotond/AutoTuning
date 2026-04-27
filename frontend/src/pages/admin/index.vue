<script setup>
import { useRouter } from 'vue-router'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'

const router = useRouter()
const authStore = useAuthStore()

if (!authStore.isAuthenticated) {
  router.push('/login')
}

const goToBrands = () => {
  router.push('/admin/brands')
}

const goToModels = () => {
  router.push('/admin/models')
}
</script>

<template>
  <BaseLayout>
    <div class="mx-auto w-full max-w-[1300px] px-4 py-8 md:px-6">
      <div class="glass-panel p-7 md:p-8">
        <h1 class="text-3xl font-extrabold text-zinc-900 md:text-4xl">Admin Felület</h1>
        <p class="mt-2 text-sm text-zinc-600 md:text-base">Mit szeretnél módosítani a katalógusban?</p>
      </div>

      <div class="mt-6 grid w-full grid-cols-1 gap-6 md:grid-cols-2">
        <div class="glass-panel group cursor-pointer p-10 text-center transition-all duration-300 hover:-translate-y-1"
          @click="goToBrands">
          <div class="flex flex-col items-center gap-4">
            <div class="text-5xl"></div>
            <h2 class="text-2xl font-semibold text-zinc-900">Gyártók kezelése</h2>
            <p class="text-zinc-600">Gyártó hozzáadása, szerkesztése és törlése</p>
            <button class="btn-primary mt-2 w-full">Gyártókhoz →</button>
          </div>
        </div>

        <div class="glass-panel group cursor-pointer p-8 text-center transition-all duration-300 hover:-translate-y-1"
          @click="goToModels">
          <div class="flex flex-col items-center gap-4">
            <div class="text-5xl"></div>
            <h2 class="text-2xl font-semibold text-zinc-900">Modellek kezelése</h2>
            <p class="text-zinc-600">Modellek hozzáadása, szerkesztése és törlése</p>
            <button class="btn-primary mt-2 w-full">Modellekhez →</button>
          </div>
        </div>
      </div>
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-panel
meta:
  title: Admin Panel
</route>
