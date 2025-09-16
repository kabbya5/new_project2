<template>
  <div class="bg-slate-200/60 dark:bg-gray-900 py-2 lg:py-4">
    <LoadingSpinner v-if="loading.isLoading('game')" />
    <div v-else class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
      Playing 
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useGameStore } from '~/stores/game'
import { useLoadingStore } from '~/stores/loading';

const route = useRoute()
const gameStore = useGameStore()
const loading = useLoadingStore(); 

// route.params সবসময় string দেয়
const id = Number(route.params.id)
loading.start('game');

onMounted(async () => {
  try{
      const response = await useApiFetch('/game/play/' + id);
      if (response.status === 1 && response.launch_url) {
          window.location.href = response.launch_url;
      } else {
          alert(response.msg || 'Failed to launch game')
      }
  }catch(error){
      alert(error);
  }
  loading.stop('game');
})
</script>