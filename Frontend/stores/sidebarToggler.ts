import { defineStore } from 'pinia'

export const useSidebarTogglerStore = defineStore('sidebarToggler', {
  state: () => ({
    isOpen: true,
  }),
  actions: {
    toggle() {
      this.isOpen = !this.isOpen
    },
    open() {
      this.isOpen = true
    },
    close() {
      this.isOpen = false
    },
  },
})