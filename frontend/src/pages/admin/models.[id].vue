<script setup>
import { onMounted, computed, ref } from 'vue'
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
const pageError = ref('')

const modelId = computed(() => route.params.id)
const isEditMode = computed(() => modelId.value && modelId.value !== 'new')
const pageTitle = computed(() => isEditMode.value ? 'Edit Model' : 'Create Model')

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

  try {
    pageError.value = ''
    await brandStore.fetchAllBrands()
    if (isEditMode.value) {
      await modelStore.fetchSingleModel(modelId.value)
    }
  } catch (error) {
    pageError.value = buildUiErrorMessage(error, 'Nem sikerült adatot betölteni.')
    router.push('/admin/models')
  }
})

const handleSubmit = async (data) => {
  try {
    pageError.value = ''
    if (isEditMode.value) {
      await modelStore.update(modelId.value, data)
    } else {
      await modelStore.create(data)
    }
    router.push('/admin/models')
  } catch (error) {
    pageError.value = buildUiErrorMessage(error, 'Nem sikerült a mentés.')
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

      <div v-if="pageError" class="mb-4 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm font-medium text-red-700">
        {{ pageError }}
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
