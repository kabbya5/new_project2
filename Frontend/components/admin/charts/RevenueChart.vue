<script lang="ts" setup>
import { computed } from 'vue'
import { useThemeStore } from '~/stores/theme'

const theme = useThemeStore()

const RevenueData = [
  { month: 'January', order: 186, return: 20, sale: 166 },
  { month: 'February', order: 305, return: 30, sale: 275 },
  { month: 'March', order: 237, return: 15, sale: 222 },
  { month: 'April', order: 73, return: 5, sale: 68 },
  { month: 'May', order: 209, return: 12, sale: 197 },
  { month: 'June', order: 214, return: 18, sale: 196 },
  { month: 'July', order: 250, return: 22, sale: 228 },
  { month: 'August', order: 280, return: 25, sale: 255 },
  { month: 'September', order: 230, return: 20, sale: 210 },
  { month: 'October', order: 260, return: 28, sale: 232 },
  { month: 'November', order: 240, return: 15, sale: 225 },
  { month: 'December', order: 300, return: 35, sale: 265 },
]

const RevenueCategories = computed(() => ({
  order: {
    name: 'Order',
    color: theme.isDark ? '#3b82f6' : '#2563eb',
  },
  return: {
    name: 'Return',
    color: theme.isDark ? '#ef4444' : '#dc2626',
  },
  sale: {
    name: 'Sale',
    color: theme.isDark ? '#16a34a' : '#22c55e',
  },
}))

const xFormatter = (i: number): string => RevenueData[i]?.month ?? ''
const yFormatter = (value: number) => value.toString()
</script>

<template>
  <BarChart
    :key="theme.isDark"
    :data="RevenueData"
    :height="275"
    :categories="RevenueCategories"
    :y-axis="['order', 'return', 'sale']"
    :xNumTicks="12"
    :radius="4"
    :y-grid-line="false"
    :x-formatter="xFormatter"
    :y-formatter="yFormatter"
    :legend-position="LegendPosition.Top"
  />
</template>