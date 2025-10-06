<template>
  <div class="flex items-center justify-center py-4">
  <div class="w-full lg:w-2/3 xl:w-1/2 bg-white dark:bg-gray-800 mx-auto rounded-lg shadow-md">
    <!-- Header -->
    <div class="flex justify-between items-center mb-2 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
      <h1 class="text-2xl font-bold text-gray-800 dark:text-white"> Transaction </h1>
    </div>

    <div class="overflow-x-auto w-full px-4 mt-2 mb-4">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-white dark:bg-gray-800 divide-y divide-gray-200">
                <tr>
                    <th class="text-left p-2" style="min-width:160px"> Order Number </th>
                    <th class="text-center p-2" style="min-width:100px"> Type </th>
                    <th class="text-center p-2" style="min-width:100px"> Status </th>
                    <th class="text-right p-2" style="min-width:100px"> Amount </th>
                    <th class="text-center p-2" style="min-width:100px"> Provider </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="transaction in transactionStore.transactions" :key="transaction.id">
                    <td class="p-2">
                        {{ transaction.order_sn }}
                    </td>
                    <td class="text-center capitalize p-2">
                        {{ transaction.type }}
                    </td>

                    <td class="text-center capitalize p-2">
                        {{ transaction.status }}
                    </td>

                    <td class="text-right p-2" v-if="transaction.affiliat_amount> 0">
                        {{transaction.amount}}
                    </td>
                    <td class="text-center p-2" v-if="transaction.refer_amount > 0">
                        {{ transaction.provider }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

  </div>
</div>

</template>

<script setup lang="ts">

definePageMeta({
  middleware: 'auth'
})


import { useAuthStore } from '~/stores/auth';
import { useTransactionStore } from '~/stores/transaction';

const transactionStore = useTransactionStore();
const authStore = useAuthStore()

const type = ref<null|string>(null);

onMounted(async () => {
  await transactionStore.fetchTransaction(
    1,                 
    20,                
    type.value,              
    authStore.user?.id 
  );
});

</script>