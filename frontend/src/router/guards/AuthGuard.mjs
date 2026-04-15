import { useAuthStore } from '@/stores/authStore'

export const requireAuth = async (to, from, next) => {
  const authStore = useAuthStore()

  // List of routes that don't require authentication
  const publicRoutes = ['/login', '/register', '/']

  if (publicRoutes.includes(to.path)) {
    next()
    return
  }

  // If user is authenticated, allow access
  if (authStore.isAuthenticated) {
    next()
    return
  }

  // If not authenticated and route requires auth, redirect to login
  next('/login')
}
