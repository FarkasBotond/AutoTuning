<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  brand: {
    type: Object,
    default: null
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['submit', 'cancel'])

const form = ref({
  name: ''
})

const errors = ref({})

watch(() => props.brand, (newBrand) => {
  if (newBrand) {
    form.value = {
      name: newBrand.name
    }
  }
}, { immediate: true })

const validate = () => {
  errors.value = {}

  if (!form.value.name) {
    errors.value.name = 'Name is required'
  } else if (form.value.name.length > 100) {
    errors.value.name = 'Name must not exceed 100 characters'
  }

  return Object.keys(errors.value).length === 0
}

const handleSubmit = () => {
  if (validate()) {
    emit('submit', form.value)
  }
}
</script>

<template>
  <form @submit.prevent="handleSubmit" class="max-w-md">
    <div class="mb-4">
      <label for="name" class="block text-gray-700 font-bold mb-2">
        Brand Name
      </label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        maxlength="100"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter brand name"
      />
      <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
    </div>

    <div class="flex gap-2">
      <button
        type="submit"
        :disabled="loading"
        class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
      >
        {{ loading ? 'Saving...' : 'Save' }}
      </button>
      <button
        type="button"
        @click="$emit('cancel')"
        :disabled="loading"
        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
      >
        Cancel
      </button>
    </div>
  </form>
</template>
