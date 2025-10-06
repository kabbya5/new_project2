<!-- components/Pagination.vue -->
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  totalPages: number
  modelValue: number
}

const props = defineProps<Props>()
const emit = defineEmits(['update:modelValue'])

const currentPage = computed({
  get: () => props.modelValue,
  set: (val: number) => emit('update:modelValue', val)
})

const pages = computed(() => {
  return Array.from({ length: props.totalPages }, (_, i) => i + 1)
})

function goToPage(page: number) {
  if (page >= 1 && page <= props.totalPages) {
    currentPage.value = page
  }
}
</script>

<template>
  <div class="flex items-center space-x-2 mt-4">
    <!-- First -->
    <button
      class="px-3 py-1 rounded bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === 1"
      @click="goToPage(1)"
    >
      First
    </button>

    <!-- Prev -->
    <button
      class="px-3 py-1 rounded bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === 1"
      @click="goToPage(currentPage - 1)"
    >
      Prev
    </button>

    <!-- Pages -->
    <button
      v-for="page in pages"
      :key="page"
      class="px-3 py-1 rounded"
      :class="[
        page === currentPage
          ? 'bg-blue-500 text-white dark:bg-blue-600 dark:text-white'
          : 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600'
      ]"
      @click="goToPage(page)"
    >
      {{ page }}
    </button>

    <!-- Next -->
    <button
      class="px-3 py-1 rounded bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === totalPages"
      @click="goToPage(currentPage + 1)"
    >
      Next
    </button>

    <!-- Last -->
    <button
      class="px-3 py-1 rounded bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-white hover:bg-gray-300 dark:hover:bg-gray-600 disabled:opacity-50"
      :disabled="currentPage === totalPages"
      @click="goToPage(totalPages)"
    >
      Last
    </button>
  </div>
</template>



