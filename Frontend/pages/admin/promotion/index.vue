<template>
    <AdminPromotionForm v-if="isModalOpen" :promotionId="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3 w-full">
        <div class="flex justify-between items-center">
            <!-- <div class="form-group">
                <input type="text" placeholder="Search promotion" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
            </div> -->

            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-1 bg-sky-500 text-white rounded-md"> Add Promotion </button>
            </div>
        </div>

        <div class="my-5">
            <LoadingSpinner v-if="loading.isLoading('promotion')" />
            <div class="overflow-x-auto" v-else>
                <table class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-card">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs uppercase tracking-wider">
                            Title
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs uppercase tracking-wider">
                            Type
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs uppercase tracking-wider">
                            Status
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs uppercase tracking-wider">
                             Image
                        </th>

                        <th scope="col" class="px-6 py-3 text-right text-xs uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="">
                        <tr v-for="promotion in promotionStore.promotions" :key="promotion.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ promotion.title }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ promotion.type }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ promotion.status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm"> 
                                <img :src="promotion.image_url" :alt="promotion.title" class="w-10">
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="createUpdateModal(promotion.id)" class="">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <button @click="deletepromotion(promotion.id)" class="ml-2 ">
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
    import { usePromotionStore} from '~/stores/promotion';
    import { useLoadingStore } from '~/stores/loading';

    const promotionStore = usePromotionStore();
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
        await promotionStore.index();
    })

    const deletepromotion = async(id:number) => {
        const confirmed = confirm("Are you sure you want to delete this promotion?");
        if (!confirmed) {
            return; 
        }
        await promotionStore.delete(id);
    }

</script>