<template>
  <div class="rounded-lg text-center">
     <p :class="[
      'text-lg font-semibold',
      positive ? 'text-green-500' : 'text-gray-800 dark:text-gray-100'
    ]">
      {{ value }}
    </p>
    <p class="text-sm text-gray-500 dark:text-gray-300">{{ title }}</p>
   
  </div>
</template>

<script setup lang="ts">
    defineProps(['title', 'value', 'positive'])
</script>