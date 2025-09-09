<template>
  <div class="bg-slate-200/60 dark:bg-gray-900 py-2 lg:py-4">
    <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
      Playing 
    </div>
  </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useGameStore } from '~/stores/game'

const route = useRoute()
const gameStore = useGameStore()

// route.params সবসময় string দেয়
const id = Number(route.params.id)

onMounted(async () => {
  await gameStore.playGame(id)
})
</script>