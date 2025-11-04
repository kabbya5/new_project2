<template>
    <AdminDepositNumberForm v-if="isModalOpen" :number_id="currentId" @close="isModalOpen=false"/>

    <div class="bg-white dark:bg-gray-800 p-3">
        <div class="flex justify-between items-center">
            <h2> Deposit Number </h2>
      
            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-[6px] bg-sky-500 text-white rounded-md"> Add Number </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                <LoadingSpinner v-if="loading.isLoading('deposit_number')" />
                <table v-else class="divide-y min-w-full table-fixed divide-gray-200 dark:divide-gray-700 rounded-lg min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Owner Name
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Provider
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Transaction Type 
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Phone Number 
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Status
                        </th>

                        <th  class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="number in numberStore.deposit_numbers" :key="number.id">
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ number.owner_name }}</td>
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ number.provider }}</td>
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ number.transaction_type }}</td>
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ number.phone_number }}</td>
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ number.status }}</td>
                            
                            <td class="px-6 py-2  text-right text-sm font-medium">
                                <button @click="createUpdateModal(number.id)" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <button @click="deleteNumber(number.id)" class="ml-2 text-red-500 hover:text-red-200">
                                    <i class="fa-solid fa-trash"></i>
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
    import { useDepositNumberStore } from '~/stores/depositNumber';
    import { useLoadingStore } from '~/stores/loading';

   const numberStore = useDepositNumberStore();
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

    const deleteNumber = async (id: number) => {
        const confirmed = confirm("Are you sure you want to delete this text?");
        if (!confirmed) return;

        await numberStore.delete(id);
    };

    onMounted (async () =>{
        await numberStore.index();        
    });

</script>