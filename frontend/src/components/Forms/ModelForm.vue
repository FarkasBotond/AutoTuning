<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  model: {
    type: Object,
    default: null
  },
  brands: {
    type: Array,
    default: () => []
  },
  loading: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['submit', 'cancel'])

const form = ref({
  brand_id: null,
  name: '',
  gen: '',
  mod: '',
  startyear: null,
  endyear: null
})

const errors = ref({})

watch(() => props.model, (newModel) => {
  if (newModel) {
    form.value = {
      brand_id: newModel.brand_id,
      name: newModel.name,
      gen: newModel.gen,
      mod: newModel.mod,
      startyear: newModel.startyear,
      endyear: newModel.endyear
    }
  }
}, { immediate: true })

const validate = () => {
  errors.value = {}

  if (!form.value.brand_id) {
    errors.value.brand_id = 'Brand is required'
  }

  if (!form.value.name) {
    errors.value.name = 'Model name is required'
  } else if (form.value.name.length > 50) {
    errors.value.name = 'Model name must not exceed 50 characters'
  }

  if (!form.value.gen) {
    errors.value.gen = 'Generation is required'
  } else if (form.value.gen.length > 50) {
    errors.value.gen = 'Generation must not exceed 50 characters'
  }

  if (!form.value.mod) {
    errors.value.mod = 'Modification is required'
  } else if (form.value.mod.length > 50) {
    errors.value.mod = 'Modification must not exceed 50 characters'
  }

  if (!form.value.startyear) {
    errors.value.startyear = 'Start year is required'
  } else if (form.value.startyear < 1900) {
    errors.value.startyear = 'Start year must be 1900 or later'
  }

  if (form.value.endyear && form.value.endyear < form.value.startyear) {
    errors.value.endyear = 'End year must be greater than start year'
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
      <label for="brand_id" class="block text-gray-700 font-bold mb-2">
        Brand
      </label>
      <select
        id="brand_id"
        v-model.number="form.brand_id"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
      >
        <option value="">Select a brand</option>
        <option v-for="brand in brands" :key="brand.id" :value="brand.id">
          {{ brand.name }}
        </option>
      </select>
      <span v-if="errors.brand_id" class="text-red-500 text-sm">{{ errors.brand_id }}</span>
    </div>

    <div class="mb-4">
      <label for="name" class="block text-gray-700 font-bold mb-2">
        Model Name
      </label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        maxlength="50"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter model name"
      />
      <span v-if="errors.name" class="text-red-500 text-sm">{{ errors.name }}</span>
    </div>

    <div class="mb-4">
      <label for="gen" class="block text-gray-700 font-bold mb-2">
        Generation
      </label>
      <input
        id="gen"
        v-model="form.gen"
        type="text"
        maxlength="50"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter generation"
      />
      <span v-if="errors.gen" class="text-red-500 text-sm">{{ errors.gen }}</span>
    </div>

    <div class="mb-4">
      <label for="mod" class="block text-gray-700 font-bold mb-2">
        Modification
      </label>
      <input
        id="mod"
        v-model="form.mod"
        type="text"
        maxlength="50"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter modification"
      />
      <span v-if="errors.mod" class="text-red-500 text-sm">{{ errors.mod }}</span>
    </div>

    <div class="mb-4">
      <label for="startyear" class="block text-gray-700 font-bold mb-2">
        Start Year
      </label>
      <input
        id="startyear"
        v-model.number="form.startyear"
        type="number"
        min="1900"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter start year"
      />
      <span v-if="errors.startyear" class="text-red-500 text-sm">{{ errors.startyear }}</span>
    </div>

    <div class="mb-4">
      <label for="endyear" class="block text-gray-700 font-bold mb-2">
        End Year (Optional)
      </label>
      <input
        id="endyear"
        v-model.number="form.endyear"
        type="number"
        min="1900"
        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Enter end year (leave empty if ongoing)"
      />
      <span v-if="errors.endyear" class="text-red-500 text-sm">{{ errors.endyear }}</span>
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
