

<template>
  <div class="flex item-center justify-center py-4">
    <div class="w-full md:w-[400px] bg-white dark:bg-gray-800">
        <div class="">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Profile</h1>
                <div class="relative">
                    <button class="p-2 rounded-full bg-white dark:bg-gray-800 shadow">
                        <i class="fas fa-bell text-gray-600 dark:text-gray-300"></i>
                    </button>
                    <!-- <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">52</span> -->
                </div>
            </div>

            <!-- Profile Card -->
            <div class="card rounded-2xl px-4 mb-4">
                <div class="flex items-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
                      {{ authStore.user?.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-gray-800 dark:text-white"> {{ authStore.user?.name }} </h2>
                        <p class="text-gray-600 dark:text-gray-400"> Member since: 2025-05-13 </p>
                    </div>
                </div>

                <!-- Withdrawal & Deposit -->
                <div class="flex justify-between mb-4">

                    <NuxtLink to="/profile/withdrow" class="flex-1 mr-2 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-arrow-up mr-2"></i> Withdrawal
                    </NuxtLink>

                    <NuxtLink to="/profile/deposite" class="flex-1 ml-2 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-arrow-down mr-2"></i> Deposit
                    </NuxtLink>

                </div>

                <!-- Wallet Info -->
                <div class="mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Main Wallet</h3>
                    <div class="bg-gray-200 dark:bg-gray-700 rounded-lg p-4 mb-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600 dark:text-gray-400">Balance</span>
                            <div @click="recallBalance" class="cursor-pointer">
                              <span v-if="!showAmount" class="font-mono text-gray-800 dark:text-white">*****</span>
                              <span v-else class="font-mono text-gray-800 dark:text-white">{{ authStore.user?.balance }}</span>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <!-- VIP Points -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">VIP Points</h3>
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-4">
                        <div class="flex justify-between text-white">
                            <span>Current Points</span>
                            <span class="font-mono"> VIP 1</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2 dark:bg-gray-700">
                            <div class="bg-yellow-400 h-2.5 rounded-full" style="width: 10%"></div>
                        </div>
                    </div>
                </div>

                <!-- Menu Options -->
                <div class="space-y-3">
                    <NuxtLink to="profile/edit" class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-user text-blue-500 mr-3"></i>
                            <span class="text-gray-800 dark:text-white">Personal Info</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span class="text-gray-800 dark:text-white">Login & Security</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <div class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span class="text-gray-800 dark:text-white">Verification</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>
                    
                    <NuxtLink to="profile/transaction" class="flex items-center justify-between p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-receipt text-orange-500 mr-3"></i>
                            <span class="text-gray-800 dark:text-white">Transaction Records</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>
                </div>
            </div>

            <!-- Bottom Navigation -->
            <!-- <div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-gray-800 shadow-lg rounded-t-2xl">
                <div class="flex justify-around p-3 max-w-md mx-auto">
                    <a href="#" class="flex flex-col items-center text-purple-600 dark:text-purple-400">
                        <i class="fas fa-clipboard-list text-lg"></i>
                        <span class="text-xs mt-1">Betting</span>
                    </a>
                    <a href="#" class="flex flex-col items-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-dice text-lg"></i>
                        <span class="text-xs mt-1">Casino</span>
                    </a>
                    <a href="#" class="flex flex-col items-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-sliders-h text-lg"></i>
                        <span class="text-xs mt-1">Slots</span>
                    </a>
                    <a href="#" class="flex flex-col items-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-wallet text-lg"></i>
                        <span class="text-xs mt-1">Deposit</span>
                    </a>
                    <a href="#" class="flex flex-col items-center text-gray-500 dark:text-gray-400">
                        <i class="fas fa-user text-lg"></i>
                        <span class="text-xs mt-1">Profile</span>
                    </a>
                </div>
            </div> -->
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


const socials = ref([
  { id: 'facebook', label: 'Facebook', icon: 'i-fa6-brands:facebook' },
  { id: 'twitter', label: 'Twitter', icon: 'i-fa6-brands:x-twitter' },
  { id: 'telegram', label: 'Telegram', icon: 'i-fa6-brands:telegram' },
  { id: 'whatsapp', label: 'Whatsapp', icon: 'i-fa6-brands:whatsapp' },
])

const baseUrl = ref('');
const showAmount = ref<boolean>(false);

onMounted(() => {
  baseUrl.value = window.location.origin;

  authStore.recallUser();
})

const recallBalance = () =>{
    authStore.recallUser();
    showAmount.value = !showAmount.value;
}

const referralUrl = computed(() =>
  authStore.user?.refer_code ? `${baseUrl.value}/register?ref=${authStore.user.refer_code}` : ''
)

const copyRefer = async () => {
  if (referralUrl.value) {
    await navigator.clipboard.writeText(referralUrl.value)
    alert('Referral URL copied!')
  }
}
</script>

