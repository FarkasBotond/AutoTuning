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
const pageError = ref('')

const brandId = computed(() => route.params.id)
const isEditMode = computed(() => brandId.value && brandId.value !== 'new')
const pageTitle = computed(() => isEditMode.value ? 'Edit Brand' : 'Create Brand')

const buildUiErrorMessage = (error, fallback) => {
  const validationErrors = error?.data?.errors

  if (validationErrors && typeof validationErrors === 'object') {
    const firstValidationMessage = Object.values(validationErrors)
      .flat()
      .find(message => typeof message === 'string' && message.trim())

    if (firstValidationMessage) {
      return firstValidationMessage
    }
  }

  return error?.message || fallback
}

onMounted(async () => {
  if (!authStore.isAuthenticated) {
    router.push('/login')
    return
  }

  if (isEditMode.value) {
    try {
      pageError.value = ''
      await brandStore.fetchSingleBrand(brandId.value)
    } catch (error) {
      pageError.value = buildUiErrorMessage(error, 'A gyártó betöltése nem sikerült.')
      router.push('/admin/brands')
    }
  }
})

const handleSubmit = async (data) => {
  try {
    pageError.value = ''
    if (isEditMode.value) {
      await brandStore.update(brandId.value, data)
    } else {
      await brandStore.create(data)
    }
    router.push('/admin/brands')
  } catch (error) {
    pageError.value = buildUiErrorMessage(error, 'A mentés nem sikerült.')
  }
}

const handleCancel = () => {
  router.push('/admin/brands')
}
</script>

<template>
  <BaseLayout>
    <div class="mx-auto w-full max-w-[1300px] px-4 py-8 md:px-6">
      <div class="mb-6 flex flex-wrap items-center gap-3">
        <button @click="() => router.push('/admin/brands')" class="btn-muted px-4 py-2">← Vissza a gyártókhoz</button>
      </div>

      <div class="mb-5">
        <h1 class="text-3xl font-extrabold text-zinc-900">{{ isEditMode ? 'Gyártó szerkesztése' : 'Új gyártó létrehozása' }}</h1>
        <p class="mt-2 text-sm text-zinc-500">Töltsd ki az alábbi mezőt, majd mentsd a módosítást.</p>
      </div>

      <div v-if="pageError" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
        {{ pageError }}
      </div>

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
  title: Gyártók oldal
  requiresAuth: true
  role: admin
</route>
