<template>
  <button @click="sidebarToggler" class="pl-3 cursor-pointer">
    <i :class="`fa-solid ${sidebar.isOpen ? 'fa-xmark' : 'fa-bars'}`"></i>
  </button>
</template>

<script setup lang="ts">
import { useSidebarTogglerStore } from '~/stores/sidebarToggler';

const sidebar = useSidebarTogglerStore();
const sidebarToggler = sidebar.toggle;
</script>