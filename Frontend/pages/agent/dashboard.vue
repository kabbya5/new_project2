<template>
  <div class="w-full">
    <AgentDashboardTopCard></AgentDashboardTopCard>
    <div class="rounded-xl shadow-sm grid grid-cols-12 gap-4">
      <!-- Summary Cards -->
      <div class="col-span-12 lg:col-span-7 bg-white dark:bg-gray-800 p-3">
        <div class="flex justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
          <h3 class="font-[400] tracking-wide title-md"> Transactions </h3>
        </div>

        <!-- Revenue Chart -->
        <div class="pt-4">
          <AgentDashboardChart />
        </div>
      </div>

      <!-- Sales by Locations -->
      <div class="col-span-12 lg:col-span-5 bg-white dark:bg-gray-800 p-3">
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
    </div>
  </div>
</template>

<script setup lang="ts">
  definePageMeta({ 
    middleware: ['agent', 'auth'],
    layout:'agent',
  });

  import { useLoadingStore } from '~/stores/loading';
  import { useTransactionStore } from '~/stores/transaction';
  import { useBettingRecordStore } from '~/stores/bettingRecord';
  import { useUserStore } from '~/stores/user';

  const loading = useLoadingStore();
  const transactionStore = useTransactionStore();
  const bettingRecordStore = useBettingRecordStore();
  const userStore = useUserStore();

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