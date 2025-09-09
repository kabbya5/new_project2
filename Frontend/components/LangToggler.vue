<template>
  <select
    v-model="selectedLocale"
    @change="changeLocale"
    class="language-select bg-white dark:bg-gray-800 text-black dark:text-white border dark:border-gray-600 
      px-1 lg:px-3 lg:py-1 rounded"
  >
    <option v-for="lang in availableLocales" :key="lang.code" :value="lang.code">
      {{ lang.code.toUpperCase() }}
    </option>
  </select>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useLocaleStore } from '~/stores/locale'

const localeStore = useLocaleStore()

const availableLocales = ref([
  { name: 'English', code: 'en' },
  { name: 'Bangla', code: 'bn' },
  { name: 'Hindi', code: 'hi' }
])

const selectedLocale = ref('en')

const changeLocale = () => {
  const newLocale = selectedLocale.value as 'en' | 'bn' | 'hi'
  localeStore.setLocale(newLocale) // Update store and sync localStorage
}

onMounted(() => {
  if (process.client) {
    const saved = localStorage.getItem('locale') as 'en' | 'bn' | 'hi' | null
    if (saved && ['en', 'bn', 'hi'].includes(saved)) {
      selectedLocale.value = saved
      localeStore.setLocale(saved)
    }
  }
})
</script>

<style scoped>
.language-select {
  transition: all 0.3s ease;
  background-color: white;
  color: black;
}

.dark .language-select {
  background-color: #1f2937; /* gray-800 */
  color: white;
  border-color: #4b5563; /* gray-600 */
}
</style>
