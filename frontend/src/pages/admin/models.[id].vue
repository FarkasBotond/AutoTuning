<script setup>
import { onMounted, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useModelStore } from '@stores/modelStore'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'
import ModelForm from '@components/Forms/ModelForm.vue'

const router = useRouter()
const route = useRoute()
const modelStore = useModelStore()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const modelId = computed(() => route.params.id)
const isEditMode = computed(() => modelId.value && modelId.value !== 'new')
const pageTitle = computed(() => isEditMode.value ? 'Edit Model' : 'Create Model')

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  try {
    await brandStore.fetchAllBrands()
    if (isEditMode.value) {
      await modelStore.fetchSingleModel(modelId.value)
    }
  } catch (error) {
    console.error('Nem sikerült adatot betölteni:', error)
    router.push('/admin/models')
  }
})

const handleSubmit = async (data) => {
  try {
    if (isEditMode.value) {
      await modelStore.update(modelId.value, data)
    } else {
      await modelStore.create(data)
    }
    router.push('/admin/models')
  } catch (error) {
    console.error('Nem sikerült a mentés:', error)
  }
}

const handleCancel = () => {
  router.push('/admin/models')
}
</script>

<template>
  <BaseLayout>
    <div class="mx-auto w-full max-w-[1300px] px-4 py-8 md:px-6">
      <div class="mb-6 flex flex-wrap items-center gap-3">
        <button @click="() => router.push('/admin/models')" class="btn-muted px-4 py-2">← Vissza a modellekhez</button>
      </div>

      <div class="mb-5">
        <h1 class="text-3xl font-extrabold text-zinc-900">{{ isEditMode ? 'Modell szerkesztése' : 'Új modell létrehozása' }}</h1>
        <p class="mt-2 text-sm text-zinc-500">Válassz gyártót, add meg a modell adatait, majd mentsd a módosítást.</p>
      </div>

      <ModelForm
        :model="modelStore.currentModel"
        :brands="brandStore.brands"
        :loading="modelStore.isLoading || brandStore.isLoading"
        @submit="handleSubmit"
        @cancel="handleCancel"
      />
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-models-detail
meta:
  title: Modellek oldal
  requiresAuth: true
  role: admin
</route>
