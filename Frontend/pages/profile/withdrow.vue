<template>
    <div>
        
        <div class="w-full h-full bg-red-800 dark:bg-green-800 flex items-center justify-center px-5 py-8">
            <div class="text-white bg-red-900 dark:bg-green-900 shadow-lg rounded-2xl w-full md:w-[450px] px-5 pt-3 pb-5">
                <div class="flex justify-between items-center mb-3 border-b pb-2">
                    <h1 class="text-2xl font-bold text-center text-white">
                        Withdraw
                    </h1>

                    <NuxtLink to="/profile/manual-withdrow" class="bg-green-500 text-white px-3 py-1 rounded-md"> Manual Withdraw (2%)</NuxtLink>
                </div>

                <div class="grid grid-cols-4 gap-2 mb-3 mt-4">
                    <div class="col-span-2"  v-for="channel in channels" :key="channel.id">
                        <label class="cursor-pointer flex items-center justify-center py-1 px-2 rounded-xl 
                                border-2 transition-all py-2 
                                border-gray-300 dark:border-gray-600
                                bg-slate-50 dark:bg-gray-700
                                hover:border-green-500 dark:hover:border-green-400
                                text-gray-800 dark:text-white"
                            :class="selectedChannel == channel.id ? 'border-green-500 dark:border-green-400' : ''"
                        >
                        <!-- Channel Logo -->
                        <NuxtImg :src="channel.img" :alt="channel.label" class="w-10 h-10 object-contain"/>
                        
                        <p class="ml-2 text-lg"> {{ channel.label }}</p>
                        
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

                <!-- Input Field -->
                <div class="mb-4">
                    <label class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Number 
                    </label>

                    <input type="text" 
                        v-model="selectedNumber"
                        placeholder="Enter withdrawal number"
                        class="w-full px-4 py-2 border rounded-lg text-gray-800 dark:text-white
                            border-gray-300 dark:border-gray-600
                            bg-white dark:bg-gray-700 focus:ring-2 focus:ring-green-500 outline-none"
                    />

                </div>

                <!-- Remark Field -->
                <div class="mb-4">
                    <label class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Remark
                    </label>

                    <div>
                        <p class="text-sm"> Withdrawal time: 24 hours</p>
                        <p class="text-sm"> Daily withdrawal 99 (Times) Remaining withdrawal 99 </p>
                        <p class="text-sm"> Main Wallet: <span class="uppercase"> {{ authStore.getUser()?.currency }} </span> {{ authStore.getUser()?.balance }} </p>


                        <p @click="recallBalance" class="cursor-pointer text-center mt-5"> 
                            <span class="rounded-md px-4 py-2 bg-gray-200/60 dark:bg-gray-700 text-gray-700 dark:text-gray-200">
                                <i v-if="loading.isLoading('recall')" class="fa-solid fa-spinner animate-spin"></i> 
                                Recall Balance
                            </span>
                        </p>
                    </div>
                </div>

                <div class="mb-4" v-if="authStore.user?.turnover > 0">
                    <label class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Withdrawal turnover requirements
                    </label>

                    <div>
                        <p class="text-sm text-red-500"> Please complete the required turnover for withdrawal. </p>
                        <p class="text-sm text-red-500"> Turnover requirements <span class="uppercase"> {{ authStore.getUser()?.currency }} </span> {{ authStore.getUser()?.turnover }} </p>
                    </div>
                </div>

                <div v-else>
                    <div class="mb-4">
                        <label class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Amount
                        </label>

                        <input type="number" v-model="amount" 
                            @input="validateDeposit"
                            class="w-full px-4 py-2 border rounded-lg text-gray-800 dark:text-white bg-white dark:bg-gray-700 focus:ring-2 focus:ring-green-500 outline-none
                                border-gray-300 dark:border-gray-600"
                        />

                        <p v-if="amount < 100 && amount !== null" class="text-red-500"> Minimum amount need to whithdraw 100 </p>

                    </div>


                    <div class="mb-4" v-if="amount >= 100 && amount !== null">
                        <label class="block text-md font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Password
                        </label>

                        <input type="text" v-model="password"
                            placeholder="Enter withdrawal number"
                            class="w-full px-4 py-2 border rounded-lg text-gray-800 dark:text-white
                                border-gray-300 dark:border-gray-600
                                bg-white dark:bg-gray-700 focus:ring-2 focus:ring-green-500 outline-none"
                        />
                    </div>

                    <!-- Submit Button -->
                    <button
                        @click="alertConfirm"
                        class="w-full text-white font-semibold py-2 rounded-lg transition shadow-md"
                        :class="{
                            'bg-green-600 hover:bg-green-700 cursor-not-allowed opacity-70 focus:ring-red-500': amount < 100 && amount !== null,
                            'bg-green-600 hover:bg-green-700': amount >= 100
                        }"
                    >
                        {{ amount < 100 && amount !== null ? 'Minimum 100 Required' : 'Submit Withdrawal' }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Confirm Modal -->
        <div v-if="showConfirmModal"
            class="fixed inset-0 flex items-center justify-center bg-black/60 z-50">
            <div
                class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-2xl w-[380px] transition-colors duration-300">
                
                <!-- Title -->
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">
                    Confirm Withdrawal
                </h2>

                <!-- Details -->
                <div class="space-y-2 text-gray-700 dark:text-gray-300">
                    <p><span class="font-semibold">Amount:</span> {{ amount }}</p>
                    <p><span class="font-semibold">Channel:</span> {{ selectedChannel }}</p>
                    <p><span class="font-semibold">Phone:</span> {{ selectedNumber }}</p>
                </div>

                <!-- Buttons -->
                <div class="mt-6 flex justify-end gap-3">
                    <button @click="showConfirmModal = false"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700
                            text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 
                            transition">
                        Cancel
                    </button>
                    <button @click="withdrow" v-if="!loading.isLoading('withdrow')" 
                        class="px-4 py-2 rounded-lg bg-green-600 text-white 
                            hover:bg-green-700 active:bg-green-800 
                            transition shadow-md">
                        OK
                    </button>

                    <button @click="withdrow" v-else  
                        class="px-4 py-2 rounded-lg bg-orange-600 text-white 
                            hover:bg-orange-400 active:bg-green-800 
                            transition shadow-md">
                        Loading ...
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useLoadingStore } from '~/stores/loading'
    import {useAuthStore} from '~/stores/auth';
    import { useErrorStore } from '~/stores/error';

    const loading = useLoadingStore();
    const authStore = useAuthStore();
    const errorStore = useErrorStore();

    const showConfirmModal = ref(false);
    const selectedChannel = ref('nagad');
    const selectedNumber = ref(authStore.getUser()?.phone)
    const user_amount = computed(() => authStore.user?.balance ?? 0);
    const amount = ref(authStore.user?.balance);
  
    const password = ref('');
     
    const channels = ref([
        { id: "nagad", label: "Nagad", img: '/images/logo/nagad.jpg' },
        { id: "bkash", label: "Bkash", img: '/images/logo/bkash.jpg' },
    ]);

    const withdrow = async () => {
        loading.start('withdrow');
        try {
            const formData = new FormData();
            formData.append('amount', amount.value);
            formData.append('selectedChannel', selectedChannel.value);
            formData.append('password', password.value);
            formData.append('phone_number', selectedNumber.value);

            const response = await useApiFetch('/withdrow', {
                method: 'POST',
                body: formData,
            });

            // if(response.status == 1){
            //     window.location.href = response.data.pay_url; 
            // }

        } catch (error) {
            alert(errorStore.message);
        }

        loading.stop('withdrow');
        // showConfirmModal.value = false;
    };

    const alertConfirm = () => {
        if(Number(amount.value) < 100){
            navigateTo('/');
        }else{
            if (!password.value || password.value.length < 8) {
                alert('Password must be at least 8 characters');
                return false;
            }else if(Number(amount.value) < 100){
                alert('Minimum deposite amount 100');
                return false;
            }else if(!selectedChannel.value){
                alert('Channel is not selected');
                return false;
            }else{
                showConfirmModal.value = true;
            }
        }   
    };

    const validateDeposit = () => {
        if (Number(amount.value) > 50000) {
            amount.value = '50000'
        }

    }

    onMounted(() =>{
        authStore.recallUser();
    })

    const recallBalance = () =>{
        authStore.recallUser();
        amount.value = user_amount.value;
    }

</script>