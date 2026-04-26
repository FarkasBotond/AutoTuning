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
  <form @submit.prevent="handleSubmit" class="glass-panel max-w-xl p-6 md:p-8">
    <div class="mb-5">
      <label for="name" class="mb-2 block text-sm font-semibold text-zinc-700">
        Gyártó neve
      </label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        maxlength="100"
        class="brand-input"
        placeholder="Pl. BMW"
      />
      <span v-if="errors.name" class="mt-1 inline-block text-sm font-medium text-red-600">{{ errors.name }}</span>
    </div>

    <div class="flex flex-wrap gap-2">
      <button
        type="submit"
        :disabled="loading"
        class="btn-primary disabled:opacity-60"
      >
        {{ loading ? 'Mentés...' : 'Mentés' }}
      </button>
      <button
        type="button"
        @click="$emit('cancel')"
        :disabled="loading"
        class="btn-muted disabled:opacity-60"
      >
        Mégse
      </button>
    </div>
  </form>
</template>
