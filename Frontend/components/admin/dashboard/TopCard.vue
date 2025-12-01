<template>
    <div class="grid grid-cols-12 gap-4 mb-4">
        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium"> Users </p>
                        <p class="mt-2 text-2xl font-bold">
                            <i class="fa-solid fa-user ml-1"></i> {{ topContents?.users ?? 0 }}
                        </p>
                    </div>

                    <!-- Right Content -->
                    <div>
                        <span 
                        :class="topContents?.usersPercentage >= 0 
                            ? 'bg-green-100 text-green-600 dark:bg-green-200/20' 
                            : 'bg-red-100 text-red-600 dark:bg-red-200/20'" 
                        class="rounded-full px-3 py-1 text-sm font-semibold">
                        {{ topContents?.usersPercentage >= 0 ? '+' : '' }}{{ topContents?.usersPercentage }}%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-span-6 lg:col-span-3 w-full">
            <div class="bg p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium"> Deposits </p>
                        <p class="mt-2 text-2xl font-bold">
                            <i class="fa-solid fa-money-check-dollar ml-1"></i> {{ topContents?.deposites ?? 0 }}
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
            <div class="bg p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium"> Withdraw </p>
                        <p class="mt-2 text-2xl font-bold">
                            <i class="fa-solid  fa-money-bill-transfer ml-1"></i> {{ topContents?.withdraws ?? 0 }}
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
            <div class="bg p-3 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                <div class="flex items-center justify-between">
                    <!-- Left Content -->
                    <div>
                        <p class="font-medium"> Profite </p>
                        <p class="mt-2 text-2xl font-bold">
                            <i class="fa-solid fa-money-check-dollar ml-1"></i> {{ topContents?.profits ?? 0 }}
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

    const topContents = ref<Object>();

    onMounted(async () => {
        try{
            const response = await useApiFetch('admin/dashboard/top/content');
            if(response){
                topContents.value = response;
            }

        }catch(error){
            alert(error);
        }
    })
</script>