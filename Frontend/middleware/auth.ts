import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app'
import { useAuthStore } from '~/stores/auth'

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()
  const token = authStore?.getToken()

  if (process.client) {
    // Guest allowed routes
    const guestRoutes = ['/login', '/register']

    // If no token and trying to access protected route → go to /login
    if (!token && !guestRoutes.includes(to.path)) {
      return navigateTo(`/login?redirect=${to.fullPath}`)
    }

    // If logged in and trying to go to guest route → redirect home
    if (token && guestRoutes.includes(to.path)) {
      return navigateTo('/')
    }
  }
})