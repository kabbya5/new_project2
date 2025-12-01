<template>
    <div class="overflow-x-auto w-full">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-card divide-gray-200">
                <tr>
                    <th class="text-left py-1" style="min-width:160px"> User Name </th>
                    <th class="text-center py-1" style="min-width:100px"> Type </th>
                    <th class="text-right py-1" style="min-width:100px"> Amount </th>
                </tr>
            </thead>
            <tbody class="divide-gray-200">
                <tr v-for="transaction in transactions.slice(0, 8)" :key="transaction.id">
                    <td class="py-1">
                        {{ transaction.user_nane }}
                    </td>
                    <td class="text-center capitalize py-1">
                        {{ transaction.type }}
                    </td>

                    <td class="text-right py-1">
                        {{ transaction.amount }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    transactions:Object[],
}>();
</script>

<style scoped>
    table th,td{
        padding: 4px 8px;
    }
</style>