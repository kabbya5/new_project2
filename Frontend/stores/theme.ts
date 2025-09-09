import { defineStore } from 'pinia'

export const useThemeStore = defineStore('theme', {
  state: () => ({
    isDark: true
  }),

  actions: {
    setDarkMode(val: boolean) {
      this.isDark = val
      localStorage.setItem('theme', val ? 'dark' : 'light')
      document.documentElement.classList.toggle('dark', val)
    },

    initTheme() {
      const storedTheme = localStorage.getItem('theme')
      const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches

      if (storedTheme === 'dark' || (!storedTheme && prefersDark)) {
        this.setDarkMode(true)
      } else {
        this.setDarkMode(false)
      }
    }
  }
})
