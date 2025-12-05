<template>
    <div class="pb-6 pt-4">
        <div class="container mx-auto w-full max-w-5xl rounded-2xl shadow-lg overflow-hidden">
        <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-2 py-4  space-y-3 sm:space-y-0 sm:space-x-4">
                <h1 class="font-semibold text-center sm:text-left">
                    Betting Records
                </h1>

                <div class="flex flex-col sm:flex-row sm:items-center w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-2">
                    <input
                    type="date"
                    v-model="from_date"
                    @change="search"
                    class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="From Date"
                    />

                    <input
                    type="date"
                    v-model="to_date"
                    @change="search"
                    class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
            <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                <thead class="bg-card uppercase text-xs font-semibold">
                    <tr>
                        <th class="text-left px-6 py-3">Transaction</th>
                        <th class="text-center px-6 py-3"> Game </th>
                        <th class="text-right px-6 py-3"> Type </th>
                        <th class="text-center px-6 py-3"> Provider</th>
                        <th class="text-left px-6 py-3"> Bet </th>
                        <th class="text-left px-6 py-3"> Win </th>
                        <th class="text-left px-6 py-3"> After Balance </th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Empty State -->
                    <tr v-if="!bettingRecordStore.records.length">
                    <td colspan="5" class="text-center py-8 ">
                        No transactions found 😔
                    </td>
                    </tr>

                    <!-- Table Rows -->
                    <tr
                    v-for="(transaction, i) in bettingRecordStore.records"
                    :key="i"
                    class="border-b border-gray-100 transition"
                    >
                    <td class="text-center px-6 py-3">
                        {{ transaction.transaction_id }}
                    </td>

                    <td class="text-center px-6 py-3">
                        {{ transaction.game }}
                    </td>

                    <td class="text-center px-6 py-3">
                        <span
                        class="px-2 py-1 text-xs font-semibold rounded-full">
                        {{ transaction.game_type }}
                        </span>
                    </td>

                    <td class="text-right px-6 py-3">
                        {{ transaction.provider }}
                    </td>

                    

                    <td class="text-center px-6 py-3">
                        {{ transaction.bet }}
                    </td>

                    <td class="text-left px-6 py-3">
                        {{ transaction.win }}
                    </td>

                    <td class="text-left px-6 py-3">
                        {{ transaction.after_balance }}
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
import { useBettingRecordStore } from '~/stores/bettingRecord';

const bettingRecordStore = useBettingRecordStore();
const authStore = useAuthStore();
const currentPage = ref(bettingRecordStore.pagination?.current_page);
const totalPages = computed(() => bettingRecordStore.pagination?.last_page ?? 1);

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

async function fetchPosts() {
    await bettingRecordStore.fetchTransaction(
        currentPage.value,
        30,
        authStore.user?.id,
        null,
        null,
        from_date.value,
        to_date.value,
    );
}

const search = (async() => {
    await bettingRecordStore.fetchTransaction(
        currentPage.value,
        30,
        authStore.user?.id,
        null,
        null,
        from_date.value,
        to_date.value,
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
