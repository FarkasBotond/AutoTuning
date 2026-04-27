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

const parseLegacyModelLabel = (label = '') => {
  const normalized = String(label || '').trim().replace(/\s+/g, ' ')

  if (!normalized) {
    return { name: '', gen: '', mod: '' }
  }

  const match = normalized.match(/^(.*?)\s+gen\s+(.+)$/i)
  if (!match) {
    return { name: normalized, gen: '', mod: '' }
  }

  return {
    name: match[1].trim(),
    gen: match[2].trim(),
    mod: ''
  }
}

const form = ref({
  brand_id: null,
  name: '',
  startyear: null,
  endyear: null
})

const errors = ref({})

watch(() => props.model, (newModel) => {
  if (newModel) {
    form.value = {
      brand_id: newModel.brand_id ?? newModel.brand?.id ?? null,
      name: newModel.name || parseLegacyModelLabel(newModel.model).name,
      startyear: newModel.startyear ?? newModel.start_year ?? null,
      endyear: newModel.endyear ?? newModel.end_year ?? null
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
  <form @submit.prevent="handleSubmit" class="glass-panel max-w-3xl p-6 md:p-8">
    <div class="mb-4">
      <label for="brand_id" class="mb-2 block text-sm font-semibold text-zinc-700">
        Gyártó
      </label>
      <select id="brand_id" v-model.number="form.brand_id" class="brand-input">
        <option value="">Válassz gyártót</option>
        <option v-for="brand in brands" :key="brand.id" :value="brand.id">
          {{ brand.name }}
        </option>
      </select>
      <span v-if="errors.brand_id" class="mt-1 inline-block text-sm font-medium text-red-600">{{ errors.brand_id
        }}</span>
    </div>

    <div class="mb-4">
      <div>
        <label for="name" class="mb-2 block text-sm font-semibold text-zinc-700">
          Modell név
        </label>
        <input id="name" v-model="form.name" type="text" maxlength="50" class="brand-input" placeholder="Pl. M3" />
        <span v-if="errors.name" class="mt-1 inline-block text-sm font-medium text-red-600">{{ errors.name }}</span>
      </div>
    </div>

    <div class="mb-4 grid grid-cols-1 gap-4 md:grid-cols-2">
      <div>
        <label for="startyear" class="mb-2 block text-sm font-semibold text-zinc-700">
          Kezdő év
        </label>
        <input id="startyear" v-model.number="form.startyear" type="number" min="1900" class="brand-input"
          placeholder="Pl. 2022" />
        <span v-if="errors.startyear" class="mt-1 inline-block text-sm font-medium text-red-600">{{ errors.startyear
          }}</span>
      </div>

      <div>
        <label for="endyear" class="mb-2 block text-sm font-semibold text-zinc-700">
          Záró év (opcionális)
        </label>
        <input id="endyear" v-model.number="form.endyear" type="number" min="1900" class="brand-input"
          placeholder="Pl. 2025" />
        <span v-if="errors.endyear" class="mt-1 inline-block text-sm font-medium text-red-600">{{ errors.endyear
          }}</span>
      </div>
    </div>

    <div class="flex flex-wrap gap-2">
      <button type="submit" :disabled="loading" class="btn-primary disabled:opacity-60">
        {{ loading ? 'Mentés...' : 'Mentés' }}
      </button>
      <button type="button" @click="$emit('cancel')" :disabled="loading" class="btn-muted disabled:opacity-60">
        Mégse
      </button>
    </div>
  </form>
</template>