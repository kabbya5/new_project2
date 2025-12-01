<template>
  <div class="min-h-screen py-10 px-4">
    <div class="container mx-auto p-4">

      <!-- Page Title -->
      <h1 class="text-3xl font-bold mb-6 text-center"> 🎖️ VIP Level </h1>

      <!-- User VIP Summary -->
      <div class="bg-card rounded-2xl p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">

          <!-- User Level Info -->
          <div>
            <h2 class="text-xl font-semibold">
              Current Level: <span class="text-yellow-400">{{ authStore.user?.level?.name}}</span>
            </h2>
            <p class="">You’ve earned {{ authStore.user?.next_level_amount }} points this week.</p>
          </div>

          <!-- Progress Bar -->
          <div class="w-full md:w-1/2">
            <div class="flex justify-between text-sm mb-1">
              <span> Progress </span>
              <span>{{ authStore.user?.level_complete }}%</span>
            </div>
            <div class="w-full bg rounded-full h-3">
              <div
                class="bg-card h-3 rounded-full transition-all duration-500"
                :style="{ width: authStore.user?.level_complete + '%' }"
              ></div>
            </div>
          </div>

        </div>
      </div>

      <!-- VIP Levels Grid -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        <div
          v-for="level in lableStore.labels"
          :key="level.id"
          class="bg-card p-6 rounded-2xl shadow-md border transition transform hover:scale-105"
          :class="authStore.user?.lebel?.name === level.name ? 'border-yellow-400 shadow-yellow-500/30' : 'border-red-700'"
        >
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-semibold">{{ level.name }}</h3>
            <span
              class="px-3 py-1 rounded-full text-sm"
              :class="authStore.user?.lebel?.name == level.name ? 'bg-yellow-400 text-black' : 'bg text-gray-300'"
            >
              {{ level.daily_bonus }} daily Bonus
            </span>
          </div>
          <p class="text-sm mb-4">Min Deposite: {{ level.min_bet }}</p>
          <p class="text-sm mb-4"> Deposit Bonus: {{ level.deposit_bonus }}</p>
          <div v-if="authStore.user?.level?.name === level.name" class="text-yellow-400 text-sm font-medium">✅ Current Level</div>
          <div v-else class="text-sm">Earn {{ level.min_bet - authStore.user?.deposit_amount> 0 ? level.min_bet - authStore.user?.deposit_amount : 0 }} more points</div>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
definePageMeta({
  middleware: 'auth',
})

import { useAuthStore } from '~/stores/auth';
import {useLabelStore} from '~/stores/label';

const authStore = useAuthStore();
const lableStore = useLabelStore();

onMounted(() => {
  lableStore.index();
  authStore.recallUser();
})

</script>