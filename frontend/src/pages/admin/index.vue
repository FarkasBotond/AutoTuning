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
    <div class="flex min-h-screen flex-col items-center justify-center px-4 py-12">
      <h1 class="text-4xl font-bold text-gray-900">Admin Felület</h1>
      <p class="mt-2 text-center text-lg text-gray-600">Mit szeretne változtatni?</p>

      <div class="mt-12 grid w-full max-w-2xl grid-cols-1 gap-8 md:grid-cols-2">
        <div 
          class="group cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-500 hover:shadow-lg" 
          @click="goToBrands"
        >
          <div class="flex flex-col items-center gap-4">
            <div class="text-5xl">🏢</div>
            <h2 class="text-2xl font-semibold text-gray-900">Gyártók változtatása</h2>
            <p class="text-gray-600">Gyártó hozzáadása, változtatása és törlése</p>
            <button class="mt-2 w-full rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition-all hover:bg-blue-700 active:scale-95">Gyártókhoz →</button>
          </div>
        </div>

        <div 
          class="group cursor-pointer rounded-xl border-2 border-gray-200 bg-white p-8 text-center shadow-sm transition-all duration-300 hover:-translate-y-1 hover:border-blue-500 hover:shadow-lg" 
          @click="goToModels"
        >
          <div class="flex flex-col items-center gap-4">
            <div class="text-5xl">🚗</div>
            <h2 class="text-2xl font-semibold text-gray-900">Modellek változtatása</h2>
            <p class="text-gray-600">Modell hozzáadása, változtatása és törlése</p>
            <button class="mt-2 w-full rounded-lg bg-blue-600 px-6 py-3 font-semibold text-white transition-all hover:bg-blue-700 active:scale-95">Modellekhez →</button>
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
