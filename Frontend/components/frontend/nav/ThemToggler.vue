<template> 
    <button @click="toggle" class="cursor-pointer"> 
        <i class="text-gray-900 dark:text-gray-100 text-[16px] md:text-[20px]"
            :class="['fa-solid', theme.isDark ? 'fa-moon' : 'fa-sun']" />
    </button>
</template>

<script setup lang="ts">
import { useThemeStore } from '~/stores/theme';
const theme = useThemeStore();

onMounted(() => {
    theme.initTheme()
})

function toggle(){
    theme.setDarkMode(!theme.isDark);
}
</script>