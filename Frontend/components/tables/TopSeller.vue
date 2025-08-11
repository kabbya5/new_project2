<template>
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                <tr>
                    <th class="text-left" style="min-width:160px"> Name </th>
                    <th class="text-center" style="min-width:100px"> Users </th>
                    <th class="text-center" style="min-width:100px"> Earning </th>
                    <th class="text-right" style="min-width:100px"> Rate </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="seller in topSeller.slice(0, 6)" :key="seller.id">
                    <td>
                        <div class="flex items-center" title="Quaomiuzzaman Kabbya">
                            <NuxtImg src="images/cricket.png" class="w-10 h-10 rounded-full" />
                            <div>
                                <p class="name ml-2"> {{strLimit('Quaomiuzzaman Kabbya', 30)}} </p>
                                <p class="name ml-2"> {{'01721597157'}} </p>
                            </div>
                            
                        </div>
                    </td>
                    <td class="text-center">
                        50 Active 3
                    </td>

                    <td class="text-center">
                        400 Takha
                    </td>

                    <td class="text-right">
                        40% <i class="fa-solid fa-chart-simple text-green-400 pl-1"></i>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
import type { topSeller } from '~/types/topSeller';

defineProps<{
    topSeller:topSeller[],
}>();
</script>

<style scoped>
    table th,td{
        padding: 4px 8px;
    }
</style>