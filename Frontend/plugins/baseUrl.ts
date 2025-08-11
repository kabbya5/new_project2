import { defineNuxtPlugin } from '#app';

export default defineNuxtPlugin((nuxtApp) => {
  const config = useRuntimeConfig();
  const baseUrl = `${config.public.baseURL.replace(/\/$/, '')}/api`;
  nuxtApp.provide('baseUrl', baseUrl);
});