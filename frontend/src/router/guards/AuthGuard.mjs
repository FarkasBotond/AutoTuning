import { useAuthStore } from '@/stores/authStore'

export const requireAuth = async (to, from, next) => {
  const authStore = useAuthStore()

  const publicRoutes = [
    '/',
    '/login',
    '/register',
    '/cart',
    '/checkout',
    '/SearchPage'
  ]

  if (publicRoutes.includes(to.path)) {
    next()
    return
  }

  if (to.meta?.requiresAuth && !authStore.isAuthenticated) {
    next('/login')
    return
  }

  if (to.meta?.role === 'admin' && !authStore.isAdmin) {
    next('/')
    return
  }

  next()
}