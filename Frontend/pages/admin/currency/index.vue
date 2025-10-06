<template>
    <AdminCurrencyForm v-if="isModalOpen" :currency_id="currentId" @close="isModalOpen=false"/>

    <div class="bg-white dark:bg-gray-800 p-3">
        <div class="flex justify-between items-center">
            <h2> Currency </h2>
      
            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-[6px] bg-sky-500 text-white rounded-md"> Add Currency </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                 <LoadingSpinner v-if="loading.isLoading('currency')" />
                <table v-else class="divide-y table-fixed divide-gray-200 dark:divide-gray-700 rounded-lg min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Currency Code
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            English Name
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            BRL Rate
                        </th>
                   
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="currency in currencyStore.currencies" :key="currency.id">
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ currency.currency_code }}</td>
                            <td class="px-6 py-2 text-sm text-gray-900 dark:text-gray-100">{{ currency.english_name }}</td>
                            <td class="px-6 py-2  text-sm text-gray-600 dark:text-gray-300">{{ currency.brl_rate }}</td>

                            <td class="px-6 py-2  text-right text-sm font-medium">
                                <button @click="createUpdateModal(currency.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useCurrencyStore } from '~/stores/currency';
    import { useLoadingStore } from '~/stores/loading';

    const currencyStore = useCurrencyStore(); 
    const loading = useLoadingStore();
    
    definePageMeta({
        middleware:['auth', 'admin'],
        layout:'admin',
    })

    const isModalOpen = ref<boolean>(false);
    const currentId = ref<number | null>(null);

    const createUpdateModal = (id:number|null = null) =>{
        currentId.value = id;
        isModalOpen.value = true;
    }

    onMounted (async () =>{
        await currencyStore.index();
        // await providerStore.fetchProviders();
        
    });

</script>