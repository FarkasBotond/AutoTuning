import { useAuthStore } from '@/stores/authStore'

export const requireAuth = async (to, from, next) => {
  const authStore = useAuthStore()

  if (!to.meta?.requiresAuth) {
    next()
    return
  }

  if (authStore.isAuthenticated) {
    next()
    return
  }

  next({
    path: '/login',
    query: {
      redirect: to.fullPath
    }
  })
}
