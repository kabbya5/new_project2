
import { useErrorStore } from '~/stores/error';
import { useAuthStore } from '~/stores/auth';

type CustomFetchOptions = {
  headers?: Record<string, string>;
  method?: 'GET' | 'POST' | 'PUT' | 'PATCH' | 'DELETE' | 'HEAD' | 'OPTIONS' | 'TRACE' | 'CONNECT';
  body?: any;
  query?: Record<string, any>;
};

export const useApiFetch = async (url: string, options: CustomFetchOptions = {}) => {
  const config = useRuntimeConfig();
  const errorStore = useErrorStore();
  const authStore = useAuthStore();

  const csrfToken = process.client ? localStorage.getItem('csrf_token') : null;
  const authToken = process.client ? authStore.getToken() : null;

  try {
    errorStore.clearError();
    const isFormData = options.body instanceof FormData;
    return await $fetch(url, {
      baseURL: `${config.public.baseURL}/api`,
      method: options.method || 'GET', // add fallback method if needed
      headers: {
        ...options.headers,
        ...(isFormData
          ? {} 
          : { 'Content-Type': 'application/json' }),
        Accept: 'application/json',
        ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
        ...(authToken ? { 'Authorization': `Bearer ${authToken}` } : {}),

      },
      body: options.body,
      query: options.query,
    });
  }catch (err: any) {
    errorStore.setError(err);
    throw err;
  }
};
