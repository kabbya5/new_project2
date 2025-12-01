<script lang="ts" setup>

import { useThemeStore } from '~/stores/theme'
import { useLoadingStore } from '~/stores/loading';

const theme = useThemeStore()
const loading = useLoadingStore();

const RevenueData = ref([])

const fetchRevenue = async () => {
  loading.start('chart');
  try {
    const res = await useApiFetch('/admin/transactions/cart/current-month') // adjust base URL
    RevenueData.value = res.data.map(item => ({
      month: item.date, 
      deposit: item.deposit,
      withdraw: item.withdraw,
    }))
  } catch (err) {
    console.error(err)
  }

  loading.stop('chart');
}

onMounted(() => {
  fetchRevenue()
})

const RevenueCategories = computed(() => ({
  deposit: {
    name: 'Deposit',
    color: '#2563eb',     // text color
    bgColor: '#dbeafe',   // light blue background
  },
  withdraw: {
    name: 'Withdraw',
    color: '#ffffff',     // white text on red
    bgColor: '#dc2626',   // red background
  },
}))

const xFormatter = (i: number): string => RevenueData.value[i]?.month ?? ''
const yFormatter = (value: number) => value.toString()
</script>

<template>
  <LoadingSpinner v-if="loading.isLoading('chart')" />
  <BarChart v-else
    :key="theme.isDark"
    :data="RevenueData"
    :height="275"
    :categories="RevenueCategories"
    :y-axis="['deposit', 'withdraw']"
    :xNumTicks="RevenueData.length"
    :radius="4"
    :y-grid-line="false"
    :x-formatter="xFormatter"
    :y-formatter="yFormatter"
    :legend-position="LegendPosition.Top"
  />
</template>
