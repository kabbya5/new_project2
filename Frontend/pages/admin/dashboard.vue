<template>

  <div class="rounded-xl shadow-sm grid grid-cols-12 gap-4">
    <!-- Summary Cards -->
    <div class="col-span-12 lg:col-span-7 bg-white dark:bg-gray-800 p-3">
      <div class="flex justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
        <h3 class="font-[400] tracking-wide title-md"> Revenue </h3>
        <div class="flex space-x-2">
            <button class="revenue-button bg-gray-300/60 dark:bg-gray-700"> ALL </button>
            <button class="revenue-button bg-gray-300/60 dark:bg-gray-700"> 1W </button>
            <button class="revenue-button bg-gray-300/60 dark:bg-gray-700"> 1M </button>
            <button class="revenue-button bg-gray-300/60 dark:bg-gray-700"> 6M </button>
            <button class="revenue-button bg-gray-300/60 dark:bg-gray-700"> 1M </button>
        </div>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 mt-2">
        <AdminDashboardSummaryCard title="Orders" value="7,585" />
        <AdminDashboardSummaryCard title="Earnings" value="$22.89k" />
        <AdminDashboardSummaryCard title="Refunds" value="367" />
        <LazyAdminDashboardSummaryCard title="Conversion Ratio" value="18.92%" positive />
      </div>

      <!-- Revenue Chart -->
      <div class="pt-4">
        <AdminChartsRevenueChart/>
      </div>
    </div>

    <!-- Sales by Locations -->
    <div class="col-span-12 lg:col-span-5 bg-white dark:bg-gray-800 p-3">
      <div class="flex relative justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
        <h3 class="font-[400] tracking-wide title-md"> Top Seller </h3>
        <div class="flex space-x-2">
          <button @click="toggleReport('seller')" class="rounded-md px-2 py-1 bg-gray-300/60 dark:bg-gray-700">
            <i class="fa-solid fa-file-arrow-up"></i>
            <span class="pl-1">Report</span>
          </button>
        </div>

        <div v-if="reports['seller']" class="mt-4 absolute right-0 top-[60%] bg-white dark:bg-gray-800 flex flex-col justify-start rounded-md shadow-md">
          <button class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Download Report
          </button>
          <button class="px-4 py-2 text-left text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Export
          </button>
        </div>
      </div>

      <div class="mt-3 w-full">
        <LoadingSpinner v-if="loading.isLoading('top-seller')" />
        <div v-else>
          <TablesTopSeller  :topSeller="topSellers.topSellers" />
        </div>
      </div>
    </div>

    <div class="col-span-12 lg:col-span-7 bg-white dark:bg-gray-800 p-3">
      <div class="flex relative justify-between item-center border-b border-gray-200 dark:border-gray-700 pb-2">
        <h3 class="font-[400] tracking-wide title-md"> Transaction History </h3>
        <div class="flex space-x-2">
          <button class="date-upper-sm rounded-md px-2 py-1 bg-gray-300/60 dark:bg-gray-700">
             <Datepicker
              v-model="selectedDate" 
              @update:model-value="handleDateChange"
              :enable-time-picker="false"
              :dark="true"
              placeholder="Date"
            />
          </button>
          <button @click="toggleReport('transactions')" class="rounded-md px-2 py-1 bg-gray-300/60 dark:bg-gray-700"> 
            <i class="fa-solid fa-filter"></i>
            <span> Filter </span> 
          </button>
        </div>

        <div v-if="reports['transactions']" class="mt-4 absolute right-0 top-[60%] bg-white dark:bg-gray-800 flex flex-col justify-start rounded-md shadow-md">
          <button class="px-4 py-2 text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Download Report
          </button>
          <button class="px-4 py-2 text-left text-gray-800 dark:text-gray-200 hover:bg-gray-200 dark:hover:bg-gray-700 transition">
            Export
          </button>
        </div>
      </div>

      <div class="mt-3 w-full">
        <LoadingSpinner v-if="loading.isLoading('top-seller')" />
        <div v-else>
          <TablesTransactionTable />
        </div>
      </div>
    </div>


  </div>

 
  </template>

<script setup lang="ts">
  definePageMeta({ 
    middleware: ['auth', 'admin'],
    layout:'admin',
  });


  import { useLoadingStore } from '~/stores/loading';
  import { useTopSellerStore } from '~/stores/topSeller';

  // input 
  const selectedDate = ref(null);

  const loading = useLoadingStore();
  const topSellers = useTopSellerStore();

  const reports = ref<Record<string, boolean>>({
    seller:false,
  });

  function handleDateChange(newDate:any) {
    console.log('Date changed:', newDate)
  }

  const toggleReport = (type: string) => {
    reports.value[type] = !reports.value[type]
  }

  onMounted( async () =>{
    await topSellers.fetchTopsellers();
    console.log(topSellers.topSellers);
  })
    
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