import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useCartStore= defineStore('cart', () => {
  const items = ref([])

  function increment(step = 1) {
    counter.value += step
  }

  const counter10X = computed(() => counter.value * 10)

  return { counter, increment, counter10X }
})