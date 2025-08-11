import {defineNuxtRouteMiddleware, navigateTo} from 'nuxt/app';
import {useAuthStore} from '~/stores/auth';

export default defineNuxtRouteMiddleware((to, from) =>{
  const authStore = useAuthStore();
  const token = authStore.getToken();
  if (process.client) {
    if (!token && to.path !== '/login') {
      return navigateTo('/login');
    }
  
    if (token && to.path === '/login') {
      return navigateTo('/');
    }
  }
})