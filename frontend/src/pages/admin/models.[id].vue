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
    console.error('Failed to load data:', error)
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
    console.error('Failed to save model:', error)
  }
}

const handleCancel = () => {
  router.push('/admin/models')
}
</script>

<template>
  <BaseLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-4xl font-bold mb-6">{{ pageTitle }}</h1>

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
  title: Model Detail
  requiresAuth: true
  role: admin
</route>
