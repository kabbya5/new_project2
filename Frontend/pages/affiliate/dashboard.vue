<template>
  <div class="w-full">
    <AffiliateDashboardTopCard></AffiliateDashboardTopCard>
    <div class="rounded-xl shadow-sm grid grid-cols-12 gap-4">
      <!-- Summary Cards -->
      <div class="col-span-12 lg:col-span-7 bg-red-900 p-3">
        <div class="flex justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
          <h3 class="font-[400] tracking-wide title-md"> Transactions </h3>
        </div>

        <!-- Revenue Chart -->
        <div class="pt-4">
          <AffiliateDashboardChart class="chart-text-white" />
        </div>
      </div>

      <!-- Sales by Locations -->
      <div class="col-span-12 lg:col-span-5 bg p-3">
        <div class="flex justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
          <h3 class="font-[400] tracking-wide title-md"> Recently Transactions </h3>
        </div>

        <div class="mt-3 w-full">
          <LoadingSpinner v-if="loading.isLoading('transaction')" />
          <div v-else>
            <TablesTopSeller  :transactions="transactionStore.transactions" />
          </div>
        </div>
      </div>

      <div class="col-span-12 bg p-3">
        <div class="flex justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
          <h3 class="font-[400] tracking-wide title-md"> Recently Users </h3>
        </div>

        <div class="mt-3 w-full">
          <LoadingSpinner v-if="loading.isLoading('transaction')" />
          <div v-else>
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm text-left">
                <thead>
                  <tr class="bg-card">
                    <th scope="col" class="py-2 px-4">User</th>
                    <th scope="col" class="py-2 px-4">Refer code</th>
                    <th scope="col" class="py-2 px-4">Type</th>
                    <th scope="col" class="py-2 px-4"> Phone </th>
                    <th scope="col" class="py-2 px-4"> Email </th>
                    <th scope="col" class="py-2 px-4 text-right">Balance</th>
                  </tr>
                </thead>

                <tbody class="">
                  <tr 
                    v-for="(item, index) in userStore.users.slice(0, 12)" 
                    :key="index" 
                    class=""
                  >
                    <td class="py-3 px-4">{{ item.user_name }}</td>
                    <td class="py-3 px-4">{{ item.refer_code }}</td>
                    <td class="py-3 px-4">{{ item.role }}</td>
                    <td class="py-3 px-4">{{ item.phone }}</td>
                    <td class="py-3 px-4">{{ item.email }}</td>
                    <td class="py-3 px-4 text-right">{{ item.balance }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup lang="ts">
  definePageMeta({ 
    middleware: ['auth', 'affiliate'],
    layout:'affiliate',
  });

  import { useLoadingStore } from '~/stores/loading';
  import { useTransactionStore } from '~/stores/transaction';
  import { useBettingRecordStore } from '~/stores/bettingRecord';
  import { useUserStore } from '~/stores/user';
  import { useAuthStore } from '~/stores/auth';

  const loading = useLoadingStore();
  const transactionStore = useTransactionStore();
  const bettingRecordStore = useBettingRecordStore();
  const userStore = useUserStore();
  const authStore = useAuthStore();

  const router = useRouter();

  onMounted( async () =>{
    await transactionStore.fetchTransaction(1,10,null,null);
    await bettingRecordStore.fetchTransaction(1,10);
    await userStore.fetchUser(1,10);
  });
    
</script>

<style scoped>
  .title-md{
    font-weight: 400;
    letter-spacing: 0.3px;
    font-size: 18px;
    line-height: 25px;
  }
  .revenue-button{
    padding: 3px 5px;
    font-size: 14px;
    transition: all 0.3s linear;
    border-radius: 20%;
  }
  .revenue-button:hover{
    background-color: #007bff;
    color: #fff;
  }
  button{
    cursor: pointer;
  }

  .date-upper-sm{
    max-width: 100px;
  }
  v-deep(.dp__input) {
    background-color: transparent !important;
  }
</style>