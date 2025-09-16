<template>
  <button 
    class="text-gray-800 dark:text-gray-100 hover:bg-text-700 text-[16px] md:text-[25px] z-50"
    @click="openChat"
  >
    💬
  </button>
</template>

<script setup lang="ts">
const openChat = () => {
  if ((window as any).Tawk_API) {
    (window as any).Tawk_API.maximize()
  } else {
    console.warn('Tawk_API not ready yet')
  }
}
</script>

