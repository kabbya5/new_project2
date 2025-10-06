<template>
    <AdminTextForm v-if="isModalOpen" :text_id="currentId" @close="isModalOpen=false"/>

    <div class="bg-white dark:bg-gray-800 p-3">
        <div class="flex justify-between items-center">
            <h2> Moving Text </h2>
      
            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-[6px] bg-sky-500 text-white rounded-md"> Add text </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                 <LoadingSpinner v-if="loading.isLoading('text')" />
                <table v-else class="divide-y table-fixed divide-gray-200 dark:divide-gray-700 rounded-lg min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            text
                        </th>

                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="text in textStore.texts" :key="text.id">
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ text.text }}</td>
                           

                            <td class="px-6 py-2  text-right text-sm font-medium">
                                <button @click="deleteText(text.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-trash text-red-500"></i>
                                </button>

                                <button @click="createUpdateModal(text.id)" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
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
    import { useTextStore } from '~/stores/text';
    import { useLoadingStore } from '~/stores/loading';

    const textStore = useTextStore(); 
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

    const deleteText = async (id: number) => {
        const confirmed = confirm("Are you sure you want to delete this text?");
        if (!confirmed) return;

        await textStore.delete(id);
    };

    onMounted (async () =>{
        await textStore.index();        
    });

</script>