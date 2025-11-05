<template>
  <div class="min-h-screen bg-red-800 dark:bg-green-800 py-4 px-4">
    <div class="max-w-md mx-auto text-white bg-red-900 dark:bg-green-900 rounded-2xl shadow-xl px-6 pt-3 pb-6">
      <div class="flex justify-between items-center mb-3 border-b pb-2">
        <h1 class="text-2xl font-bold text-center text-white">
          {{ deposite }}
        </h1>

        <NuxtLink to="/profile/manual-deposit" class="bg-green-500 text-white px-3 py-1 rounded-md"> Manual Deposite (5%)</NuxtLink>
      </div>
      <!-- Title -->
      

      <!-- Quick Select Balance -->
      <div class="mb-3">
        <h2 class="text-[16px] font-medium mb-2 text-white">
          {{selectBalance}}
        </h2>
        <div class="grid grid-cols-10 gap-2">
            <div class="col-span-2" v-for="amount in quickAmounts"
                    :key="amount">
                <button
                    @click="depositAmount = amount"
                    class="w-full py-1 rounded-lg font-semibold border-2 
                        border-gray-300 dark:border-gray-600
                        bg-slate-50 
                        text-gray-800 
                        hover:border-green-500 dark:hover:border-green-400
                        transition-all"
                >
                    {{ amount }}
                </button>
            </div>
        </div>
      </div>

      <!-- Deposit Amount Input -->
      <div class="mb-3">
        <label class="block text-[16px] font-medium mb-2 text-white">
          {{enterAmount}} <span class="uppercase"> ({{currency}}) </span>
        </label>
        <input 
            type="number"
            v-model.number="depositAmount"
            min="100" 
            max="25000"
            :placeholder="enterAmount"
            @input="validateDeposit"
            class="w-full px-4 py-2 border rounded-lg text-gray-800 dark:text-white
                    border-gray-300 dark:border-gray-600
                    bg-white dark:bg-gray-700 focus:ring-2 focus:ring-green-500 outline-none"
            />
        <p class="text-red-500 text-[13px] lg:text-[14px] mt-1"> {{ enterAmountText }} </p>
      </div>

      <!-- Payment Channel -->
        <div class="mb-3">
            <h2 class="text-[16px] font-medium mb-3 text-white">
                {{selectPaymentChannel}}
            </h2>

            <div class="grid grid-cols-3 gap-5 mb-6">
                <div class="col-span-1" v-for="channel in channels" :key="channel.id">
                    <label
                    class="cursor-pointer flex items-center justify-center py-2 px-2 rounded-xl 
                            border-2 transition-all
                            border-gray-300 dark:border-gray-600
                            bg-slate-50 
                            hover:border-green-500 dark:hover:border-green-400
                            text-gray-800"
                    :class="selectedChannel == channel.id ? 'border-green-500 dark:border-green-400' : ''"
                    >
                    <!-- Channel Logo -->
                    <img 
                        :src="channel.img" 
                        :alt="channel.label"
                        class="w-10 h-auto object-contain"
                    />

                    <!-- Hidden Input -->
                    <input
                        type="radio"
                        name="channel"
                        :value="channel.id"
                        v-model="selectedChannel"
                        class="hidden"
                    />

                    </label>
                </div>
            </div>
        </div>

      <!-- Confirm Button -->
      <button v-if="!loading.isLoading('deposite')" type="button"
        class="w-full py-3 rounded-xl text-white font-bold shadow-lg
               bg-gradient-to-r from-green-500 to-green-600
               hover:from-green-600 hover:to-green-700
               active:scale-95 transition-all duration-200
               dark:from-green-400 dark:to-green-500"
        @click="confirmDeposit"
      >
        {{ submit }}
      </button>

      <button v-else type="button"
        class="w-full py-3 rounded-xl text-white font-bold shadow-lg
               bg-gradient-to-r from-red-500 to-red-600
               hover:from-red-600 hover:to-red-700
               active:scale-95 transition-all duration-200
               dark:from-red-400 dark:to-red-500"
        @click="confirmDeposit"
      >
        Loading ...
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">

import { useLoadingStore } from '~/stores/loading';
import { useAuthStore } from '~/stores/auth';
import { useLocaleStore } from '~/stores/locale';

const localeStore = useLocaleStore();
const authStore = useAuthStore();
const loading = useLoadingStore();

const deposite = computed(() => localeStore.getTranslate('deposite'))
const selectBalance = computed(() => localeStore.getTranslate('selectBalance'))
const enterAmount = computed(() => localeStore.getTranslate('enterAmount'))
const enterAmountText = computed(() => localeStore.getTranslate('enterAmountText'))
const selectPaymentChannel = computed(() => localeStore.getTranslate('selectPaymentChannel'))
const selectBonus = computed(() => localeStore.getTranslate('selectBonus'))
const submit = computed(() => localeStore.getTranslate('submit'))


const currency = computed(() => authStore.getUser()?.currency)

// Quick amounts
const quickAmounts = [100, 200, 300, 500, 1000, 2000, 5000, 10000, 15000, 25000];

// State
const depositAmount = ref(500);
const selectedChannel = ref('nagad');

// Channels
const channels = ref([
  { id: "nagad", label: "Nagad", img: '/images/logo/nagad.jpg' },
  { id: "bkash", label: "Bkash", img: '/images/logo/bkash.jpg' },
]);


// Action
const confirmDeposit = async () => {
  loading.start('deposite');
  try {
    const formData = new FormData();
    formData.append('depositAmount', depositAmount.value);
    formData.append('selectedChannel', selectedChannel.value);

    const response = await useApiFetch('/deposite', {
      method: 'POST',
      body: formData,
    });

    if(response.status == 1){
      window.location.href = response.data.pay_url; 
    }

  } catch (error) {
    console.log(error.message || error);
  }

  loading.stop('deposite');
};

const validateDeposit = () => {
  if (depositAmount.value < 100) {
    depositAmount.value = 100
  }
  if (depositAmount.value > 25000) {
    depositAmount.value = 25000
  }
}

</script>
