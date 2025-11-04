import { defineStore } from 'pinia';
import { ref, watch, onMounted } from 'vue';

export const useDarkModeStore = defineStore('darkMode', () => {
  const isDark = ref<boolean>(false);

  onMounted(() => {
    if (process.client) {
      const storedDarkMode = localStorage.getItem('darkMode');
      isDark.value = storedDarkMode === 'true';
      document.documentElement.classList.toggle('dark', isDark.value);
    }
  });

  const toggleDarkMode = () => {
    isDark.value = !isDark.value; // Toggle the dark mode state
    document.documentElement.classList.toggle('dark', isDark.value);
    if (process.client) {
      localStorage.setItem('darkMode', String(isDark.value)); // Save to localStorage
    }
  };

  watch(isDark, (newVal) => {
    document.documentElement.classList.toggle('dark', newVal);
  });

  return { isDark, toggleDarkMode }; // Return both state and method
});




