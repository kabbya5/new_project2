
import { defineNuxtRouteMiddleware, navigateTo } from 'nuxt/app';
import { useAuthStore } from '~/stores/auth';

export default defineNuxtRouteMiddleware(() => {
  const authStore = useAuthStore();

  const user = authStore.getUser();
  const role = user?.role;

  if (process.client && !user) {
    if (role !== 'admin') {
      return navigateTo('/unauthorized');
    }
  }
});
