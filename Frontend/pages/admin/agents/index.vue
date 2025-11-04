<template>
  <AdminAddBalance :show="openModal" :userId="selectedUserId" @close="openModal=false" />
  <div class="container mx-auto pb-6 pt-1">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-lg overflow-x-auto">
      <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between px-2 py-4 border-b border-gray-200 dark:border-gray-700 space-y-3 sm:space-y-0 sm:space-x-4">
            <h1 class="font-semibold text-gray-800 dark:text-gray-100 text-center sm:text-left">
                Users
            </h1>

            <div class="flex flex-col sm:flex-row sm:items-center w-full sm:w-auto space-y-2 sm:space-y-0 sm:space-x-2">
                <input
                    type="text"
                    v-model="searchQuery"
                    @keyup="search"
                    class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Search by User name, Email, Phone"
                />

                <input
                    type="date"
                    v-model="from_date"
                    @change="search"
                    class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="From Date"
                />

                <input
                type="date"
                v-model="to_date"
                @change="search"
                class="w-full sm:w-auto px-3 py-1 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="To Date"
                />

                <button
                @click="fetchPosts"
                class="w-full sm:w-auto flex items-center justify-center px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                <i class="fas fa-sync-alt"></i>
                <span class="ml-1 sm:hidden">Refresh</span>
                </button>
            </div>
        </div>


      <!-- Table -->
      <LoadingSpinner v-if="loading.isLoading('users')" />

      <div v-else class="overflow-x-auto">
        <table class="w-full text-sm text-gray-700 dark:text-gray-200">
          <thead class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 uppercase text-xs font-semibold">
            <tr>
              <th class="text-left px-6 py-3 w-fit"> User Name </th>
              <th class="text-right px-6 py-3 w-fit"> Balance </th>
              <th class="text-center px-6 py-3 w-fit"> Phone </th>
              <th class="text-center px-6 py-3 w-fit"> Email </th>
              <th class="text-center px-6 py-3 w-fit"> Created At </th>
              <th class="text-center px-6 py-3 w-fit"> Action </th>
            </tr>
          </thead>

          <tbody>
            <!-- Empty State -->
            <tr v-if="!userStore.users.length">
              <td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                No users found 😔
              </td>
            </tr>

            <!-- Table Rows -->
            <tr
              v-for="(user, i) in userStore.users"
              :key="i"
              class="border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800 transition"
            >
              <td class="px-6 py-3 font-medium text-gray-800 dark:text-gray-100">
                {{ user.user_name }}
              </td>

              <td class="text-right px-6 py-3">
                {{ user.balance }} <span class="uppercase"> {{user.currency }}</span>
              </td>

              <td class="text-center px-6 py-3">
                {{ user.phone}}
              </td>

               <td class="text-left px-6 py-3">
                {{ user.email }}
              </td>
               <td class="text-left px-6 py-3">
                {{ user.created_at}}
              </td>
              <td class="text-left px-6 py-3">
                  <button @click="open(user.id)" class="bg-orange-600 mr-2 text-white transaction duration-300 hover:bg-green-800 text-white py-1 px-2 rounded-md"> Add Balance </button>
                  <NuxtLink :to="`/admin/user/${user?.id}`" class="bg-green-600 text-white transaction duration-300 hover:bg-green-800 text-white py-1 px-2 rounded-md"> Edit </NuxtLink>
              </td>
            </tr>
          </tbody>
        </table>

        <Pagination v-if="totalPages > 1" v-model="currentPage" :total-pages="totalPages" />

      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: ['auth', 'admin'],
  layout:'admin',
})

import { useAuthStore } from '~/stores/auth';
import { useUserStore } from '~/stores/user';
import { useLoadingStore } from '~/stores/loading';

const userStore = useUserStore();
const authStore = useAuthStore();
const loading = useLoadingStore();
const currentPage = ref(userStore.pagination?.current_page);
const totalPages = computed(() => userStore.pagination?.last_page ?? 1);

const today = new Date()

const from_date = ref();
const to_date = ref();
const type = ref('agent');
const searchQuery = ref<null|string>(null);
const openModal = ref(false);
const selectedUserId = ref<null|number>(null);

async function fetchPosts() {
    await userStore.fetchUser(
        currentPage.value,
        30,
        type.value,
        searchQuery.value,
        from_date.value,
        to_date.value,
    );
}

const search = (async() => {
    await userStore.fetchUser(
        currentPage.value,
        30,
        type.value,
        searchQuery.value,
        from_date.value,
        to_date.value,
    );
});

const open = (id: number) => {
  selectedUserId.value = id
  openModal.value = true
}

watch(currentPage, fetchPosts, { immediate: true })

</script>

<style scoped>
table th,
table td {
  white-space: nowrap;
}
</style>
