<template>
    <div class="grid grid-cols-12 gap-4 mb-4">

        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium text-gray-500 dark:text-gray-400"> Balance </p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            <i class="fa-solid  fa-money-bill-transfer text-blue-500 ml-1"></i>  {{ userBalance ?? 0 }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium text-gray-500 dark:text-gray-400"> Deposits </p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            <i class="fa-solid fa-money-check-dollar text-blue-500 ml-1"></i> {{ topContents?.deposites ?? 0 }}
                        </p>
                    </div>

                    <!-- Right Content -->
                    <div>
                        <span 
                        :class="topContents?.depositesPercentage >= 0 
                            ? 'bg-green-100 text-green-600 dark:bg-green-200/20' 
                            : 'bg-red-100 text-red-600 dark:bg-red-200/20'" 
                        class="rounded-full px-3 py-1 text-sm font-semibold">
                        {{ topContents?.depositesPercentage >= 0 ? '+' : '' }}{{ topContents?.depositesPercentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium text-gray-500 dark:text-gray-400"> Withdraw </p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            <i class="fa-solid  fa-money-bill-transfer text-blue-500 ml-1"></i> {{ topContents?.withdraws ?? 0 }}
                        </p>
                    </div>

                    <!-- Right Content -->
                    <div>
                        <span 
                        :class="topContents?.withdrawPercentage >= 0 
                            ? 'bg-green-100 text-green-600 dark:bg-green-200/20' 
                            : 'bg-red-100 text-red-600 dark:bg-red-200/20'" 
                        class="rounded-full px-3 py-1 text-sm font-semibold">
                        {{ topContents?.withdrawPercentage >= 0 ? '+' : '' }}{{ topContents?.withdrawPercentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg-white dark:bg-gray-800 p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium text-gray-500 dark:text-gray-400"> Profite </p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">
                            <i class="fa-solid fa-money-check-dollar text-blue-500 ml-1"></i> {{ topContents?.profits ?? 0 }}
                        </p>
                    </div>

                    <!-- Right Content -->
                    <div>
                        <span 
                        :class="topContents?.profitsPercentage >= 0 
                            ? 'bg-green-100 text-green-600 dark:bg-green-200/20' 
                            : 'bg-red-100 text-red-600 dark:bg-red-200/20'" 
                        class="rounded-full px-3 py-1 text-sm font-semibold">
                        {{ topContents?.profitsPercentage >= 0 ? '+' : '' }}{{ topContents?.profitsPercentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
    import {useAuthStore} from '~/stores/auth';
    const authStore = useAuthStore();
    const userBalance = computed(() => authStore.user?.balance ?? 0)
    const topContents = ref<Object>();

    onMounted(async () => {
        try{
            const response = await useApiFetch('/agent/top/content');
            if(response){
                topContents.value = response;
            }
            

            console.log('top', topContents.value);
        }catch(error){
            alert(error);
        }
    })
</script>