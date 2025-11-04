<script setup lang="ts">
import Sidebar from '~/components/admin/Sidebar.vue';
import Topbar from '~/components/admin/Topbar.vue';

</script>

<template>
  <div class="h-screen flex overflow-hidden bg-slate-200/60 dark:bg-gray-900 text-gray-900 dark:text-gray-300">
    <!-- Sidebar (fixed height, scrollable if long) -->
    <Sidebar class="w-full h-full overflow-y-auto" />

    <!-- Main content area -->
    <div class="flex flex-col overflow-hidden w-full">
      <Topbar />

      <!-- Scrollable page content -->
      <main class="flex overflow-y-auto p-4">
        <slot />
      </main>
    </div>
  </div>
</template>