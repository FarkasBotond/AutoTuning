<script setup>
import { onMounted, computed, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useBrandStore } from '@stores/brandStore'
import { useAuthStore } from '@stores/authStore'
import BaseLayout from '@layouts/BaseLayout.vue'
import BrandForm from '@components/Forms/BrandForm.vue'

const router = useRouter()
const route = useRoute()
const brandStore = useBrandStore()
const authStore = useAuthStore()

const brandId = computed(() => route.params.id)
const isEditMode = computed(() => brandId.value && brandId.value !== 'new')
const pageTitle = computed(() => isEditMode.value ? 'Edit Brand' : 'Create Brand')

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  if (isEditMode.value) {
    try {
      await brandStore.fetchSingleBrand(brandId.value)
    } catch (error) {
      console.error('Failed to load brand:', error)
      router.push('/admin/brands')
    }
  }
})

const handleSubmit = async (data) => {
  try {
    if (isEditMode.value) {
      await brandStore.update(brandId.value, data)
    } else {
      await brandStore.create(data)
    }
    router.push('/admin/brands')
  } catch (error) {
    console.error('Failed to save brand:', error)
  }
}

const handleCancel = () => {
  router.push('/admin/brands')
}
</script>

<template>
  <BaseLayout>
    <div class="container mx-auto p-6">
      <h1 class="text-4xl font-bold mb-6">{{ pageTitle }}</h1>

      <BrandForm
        :brand="brandStore.currentBrand"
        :loading="brandStore.isLoading"
        @submit="handleSubmit"
        @cancel="handleCancel"
      />
    </div>
  </BaseLayout>
</template>

<route lang="yaml">
name: admin-brands-detail
meta:
  title: Brand Detail
  requiresAuth: true
  role: admin
</route>
