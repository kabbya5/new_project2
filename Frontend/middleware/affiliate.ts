import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app'
import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  const authStore = useAuthStore()
  const token = authStore.getToken()

  // guest routes
  const guestRoutes = ['/login', '/register']

  if (process.client) {
    if (!token && !guestRoutes.includes(to.path)) {
      return navigateTo(`/login?redirect=${to.fullPath}`)
    }

    // যদি শুধু token থাকে, তবে reload-এ current page allow করো
    if (token && guestRoutes.includes(to.path)) {
      const redirectPath = to.query.redirect || '/'
      return navigateTo(redirectPath as string)
    }

    // admin route guard
    if (token && authStore.user && authStore.user.role !== 'affiliate' && to.path.startsWith('/affiliate')) {
      return navigateTo('/')
    }
  }
})
