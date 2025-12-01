<template>
  <div class="pb-6 pt-4">
    <div class="container mx-auto w-full text-white max-w-5xl rounded-2xl shadow-lg overflow-hidden">
      <!-- Header -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-2 py-4 border-b border-gray-200 dark:border-gray-700 space-y-3 sm:space-y-0 sm:space-x-4">
        <h1 class="font-semibold text-center sm:text-left">
            Transaction Records
        </h1>

        <div class="flex flex-col sm:flex-row sm:items-center w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-2">
            <select
              v-model="type"
              @change="search"
              class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
              <option value="deposit">Deposit</option>
              <option value="withdraw"> Withdraw </option>
              <option value="all_transaction"> All </option>
            </select>

            <input
            type="date"
            v-model="from_date"
            @change="search"
            class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="From Date"
            />

            <input
            type="date"
            v-model="to_date"
            @change="search"
            class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="To Date"
            />

            <button
            @click="fetchPosts"
            class="w-full sm:w-auto flex items-center justify-center px-3 py-1 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
            <i class="fas fa-sync-alt"></i>
            <span class="ml-1 sm:hidden">Refresh</span>
            </button>
        </div>
      </div>


      <!-- Table -->
       <LoadingSpinner v-if="loading.isLoading('transaction')" />

      <div v-else class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-card uppercase text-xs font-semibold">
            <tr>
              <th class="text-left px-6 py-3">Order #</th>
              <th class="text-center px-6 py-3">Type</th>
              <th class="text-center px-6 py-3"> Created At </th>
              <th class="text-right px-6 py-3"> Amount</th>
              <th class="text-center px-6 py-3">  Status </th>
              <th class="text-center px-6 py-3"> Provider</th>
              <th class="text-left px-6 py-3"> Remark </th>
            </tr>
          </thead>

          <tbody>
            <!-- Empty State -->
            <tr v-if="!transactionStore.transactions.length">
              <td colspan="5" class="text-center py-8">
                No transactions found 😔
              </td>
            </tr>

            <!-- Table Rows -->
            <tr
              v-for="(transaction, i) in transactionStore.transactions"
              :key="i"
              class="border-b border-gray-100"
            >
              <td class="px-6 py-3 font-medium">
                {{ transaction.order_sn }}
              </td>

              <td class="text-center px-6 py-3">
                <span
                  class="px-2 py-1 text-xs font-semibold rounded-full"
                  :class="{
                    'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200': transaction.type === 'deposit',
                    'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200': transaction.type === 'withdraw',
                    'bg-yellow-100 text-yellow-700 dark:bg-yellow-800 dark:text-yellow-200': transaction.type === 'bonus'
                  }"
                >
                  {{ transaction.type }}
                </span>
              </td>
              <td class="text-center px-6 py-3">
                <span
                  class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ transaction.created_time }}
                </span>
              </td>

              <td class="text-right px-6 py-3">
                {{ transaction.amount.toLocaleString() }} <span class="uppercase"> {{authStore.user?.currency }}</span>
              </td>

              <td class="text-center px-6 py-3">
                <span class="px-2 py-1 text-xs font-semibold rounded-full" 
                  :class="{
                    'bg-blue-100 text-blue-700 dark:bg-blue-800 dark:text-blue-200': transaction.status === 'pending',
                    'bg-green-100 text-green-700 dark:bg-green-800 dark:text-green-200': transaction.status === 'success',
                    'bg-red-100 text-red-700 dark:bg-red-800 dark:text-red-200': transaction.status === 'failed'
                  }">{{ transaction.status }}</span>
              </td>

              <td class="text-center px-6 py-3">
                {{ transaction.provider || '-' }}
              </td>

               <td class="text-left px-6 py-3">
                {{ transaction.remark || '-' }}
              </td>
            </tr>
          </tbody>
        </table>

        <Pagination v-model="currentPage" :total-pages="totalPages" />

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
import { useLoadingStore } from '~/stores/loading';

const transactionStore = useTransactionStore();
const authStore = useAuthStore();
const loading = useLoadingStore();
const currentPage = ref(transactionStore.pagination?.current_page);
const totalPages = computed(() => transactionStore.pagination?.last_page ?? 1);

const today = new Date()

// Get first and last day of current month
function formatDate(date) {
  const y = date.getFullYear();
  const m = String(date.getMonth() + 1).padStart(2, '0'); // month is 0-indexed
  const d = String(date.getDate()).padStart(2, '0');
  return `${y}-${m}-${d}`; // YYYY-MM-DD
}

const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

const from_date = ref(formatDate(firstDay));
const to_date = ref(formatDate(lastDay));
const status = ref('all');
const type = ref('all_transaction');

async function fetchPosts() {
    await transactionStore.fetchTransaction(
        currentPage.value,
        30,
        type.value,
        null,
        null,
        from_date.value,
        to_date.value,
        status.value,
    );
}

const search = (async() => {
    await transactionStore.fetchTransaction(
        currentPage.value,
        30,
        type.value,
        null,
        null,
        from_date.value,
        to_date.value,
        status.value,
    );
})

watch(currentPage, fetchPosts, { immediate: true })

</script>

<style scoped>
table th,
table td {
  white-space: nowrap;
}
</style>
