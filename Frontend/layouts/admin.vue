<script setup lang="ts">
import Sidebar from '~/components/admin/Sidebar.vue';
import Topbar from '~/components/admin/Topbar.vue';

</script>

<template>
    <div class="bg-slate-200/60 dark:bg-gray-900 text-gray-900 dark:text-gray-300">
        <div class="flex">
            <Sidebar />
            <div class="flex-1 flex flex-col">
                <Topbar/> 
                <main class="flex-1 p-4">
                    <slot />
                </main>
            </div>
        </div>
    </div>
    
</template>