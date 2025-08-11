import { defineNuxtPlugin } from '#app';

export default defineNuxtPlugin(async (nuxtApp) => {
  const config = useRuntimeConfig();
  const baseURL = config.public.baseURL;

  // Function to fetch and store CSRF token
  const fetchCsrfToken = async () => {
    try {

    const { data, error } = await useFetch(`${baseURL}/sanctum/csrf-cookie`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
      },
      credentials: 'include',
    });

    if (error.value) {
      throw new Error(error.value.message);
    }

    const csrfToken = document.cookie
        .split('; ')
        .find(row => row.startsWith('XSRF-TOKEN='))
        ?.split('=')[1]; 

      if (csrfToken) {
        localStorage.setItem('csrf_token', csrfToken);
        
      } else {
        console.error('CSRF token not found in cookies');
      }
    } catch (error) {
      console.error('Error fetching CSRF token:', error);
    }
  };

  // Fetch CSRF token on every page load
  nuxtApp.hook('app:mounted', () => {
    fetchCsrfToken();
  });
});