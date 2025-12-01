<template>
    <AdminLabelForm v-if="isModalOpen" :text_id="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3">
        <div class="flex justify-between items-center">
            <h2> VIP Level </h2>
      
            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-[6px] bg-sky-500 text-white rounded-md"> Add Level </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                <LoadingSpinner v-if="loading.isLoading('label')" />
                <table v-else class="divide-y min-w-full table-fixed rounded-lg min-w-full">
                    <thead class="bg-card">
                    <tr>
                        <th  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Name
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Position
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Min Deposit
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Max Deposit
                        </th>

                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Deposit Bonus
                        </th>

                        <th  class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Daily Bonus
                        </th>

                        <th  class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 ">
                        <tr v-for="label in labelStore.labels" :key="label.id">
                            <td class="px-6 py-2  text-sm">{{ label.name }}</td>
                            <td class="px-6 py-2  text-sm">{{ label.position }}</td>
                            <td class="px-6 py-2  text-sm">{{ label.min_bet }}</td>
                            <td class="px-6 py-2  text-sm">{{ label.max_bet }}</td>
                            <td class="px-6 py-2  text-sm">{{ label.deposit_bonus }}</td>
                            <td class="px-6 py-2  text-sm">{{ label.daily_bonus }}</td>
                            <td class="px-6 py-2  text-right text-sm font-medium">
                                <button @click="createUpdateModal(label.id)" class="ml-2">
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
    import { useLabelStore } from '~/stores/label';
    import { useLoadingStore } from '~/stores/loading';

    const labelStore = useLabelStore(); 
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

        await labelStore.delete(id);
    };

    onMounted (async () =>{
        await labelStore.index();        
    });

</script>