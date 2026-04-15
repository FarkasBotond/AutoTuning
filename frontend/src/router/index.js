import { createRouter, createWebHistory } from 'vue-router'
import { setTitle } from '@/router/guards/SetTitleGuard.mjs'
import { requireAuth } from '@/router/guards/AuthGuard.mjs'
import { routes } from 'vue-router/auto-routes'

export const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach(requireAuth)
router.beforeEach(setTitle)
