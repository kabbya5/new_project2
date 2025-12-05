<script setup lang="ts">
// import Sidebar from '~/components/affiliate/Sidebar.vue';
// import Topbar from '~/components/affiliate/Topbar.vue';

</script>

<template>
  <div class="h-screen flex overflow-hidden">
    <!-- Sidebar (fixed height, scrollable if long) -->
    <AffiliateSidebar class="w-full h-full overflow-y-auto" />

    <!-- Main content area -->
    <div class="flex flex-col overflow-hidden w-full">
      <AffiliateTopbar />

      <!-- Scrollable page content -->
      <main class="flex overflow-y-auto p-4">
        <slot />
      </main>
    </div>
  </div>
</template>