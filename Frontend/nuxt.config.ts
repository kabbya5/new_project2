// https://nuxt.com/docs/api/configuration/nuxt-config
import tailwindcss from "@tailwindcss/vite";
export default defineNuxtConfig({
  compatibilityDate: '2025-05-15',
  devtools: { enabled: true },
  pages: true,
  runtimeConfig: {
    public: {
      REVERB_APP_ID:process.env.NUXT_PUBLIC_REVERB_APP_ID,
      REVERB_APP_KEY:process.env.NUXT_PUBLIC_REVERB_APP_KEY,
      REVERB_APP_SECRET:process.env.NUXT_PUBLIC_REVERB_APP_SECRET,
      REVERB_HOST:process.env.NUXT_PUBLIC_REVERB_HOST,
      REVERB_PORT:process.env.NUXT_PUBLIC_EVERB_PORT,
      REVERB_SCHEME:process.env.NUXT_PUBLIC_EVERB_SCHEME,
      baseURL: process.env.NUXT_PUBLIC_BASE_URL || 'http://localhost:8000',
    },
  },

  app: {
    head: {
      script: [
        {
          innerHTML: `
            (function () {
              try {
                const theme = localStorage.getItem('theme');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                if (theme === 'dark' || (!theme && prefersDark)) {
                  document.documentElement.classList.add('dark');
                }
              } catch (_) {}
            })();
          `,
          tagPosition: 'head',
        }
      ]
    }
  },

  css: ['~/assets/css/main.css','@fortawesome/fontawesome-free/css/all.min.css'],
  
  modules: ['@nuxt/image', '@pinia/nuxt', 'nuxt-charts', 'nuxt-swiper'],
  plugins: ['~/plugins/vue-multiselect',],

  vite: {
    plugins: [
      tailwindcss(),
    ],
  },
})