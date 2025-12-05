<script setup lang="ts">
import Sidebar from '~/components/agent/Sidebar.vue';
</script>

<template>
  <div class="h-screen flex overflow-hidden bg">
    <!-- Sidebar (fixed height, scrollable if long) -->
    <Sidebar class="w-full h-full overflow-y-auto" />

    <!-- Main content area -->
    <div class="flex flex-col overflow-hidden w-full">
      <AgentTopbar />

      <!-- Scrollable page content -->
      <main class="flex overflow-y-auto p-4">
        <slot />
      </main>
    </div>
  </div>
</template>