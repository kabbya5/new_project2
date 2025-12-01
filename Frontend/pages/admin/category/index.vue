<template>
    <AdminCategoryForm v-if="isModalOpen" :categoryId="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3">
        <div class="flex justify-between items-center">
            <div class="form-group">
                <input type="text" placeholder="Search Category" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
            </div>

            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-1 bg-sky-500 text-white rounded-md"> Add Category </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto">
                <LoadingSpinner v-if="loading.isLoading('category')" />
                <table v-else class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-card">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            English Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Bangla Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Hindi Name
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Image
                        </th>

                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    <tr v-for="category in categoryStore.categories" :key="category.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ category.english_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ category.bangla_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"> {{ category.hindi_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <img :src="category.image_url" :alt="category.english_name" class="w-7">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="createUpdateModal(category.id)" class="">
                                <i class="fa-solid fa-edit"></i>
                            </button>

                            <!-- <button @click="deleteCategory(category.id)" class="ml-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                <i class="fa-solid fa-trash"></i>
                            </button> -->
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { AdminCategoryForm } from '#components';
    import { useCategoryStore } from '~/stores/category';
    import { useLoadingStore } from '~/stores/loading';

    const categoryStore = useCategoryStore();
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
        if(categoryStore.categories.length === 0){
            await categoryStore.fetchCategories();
        } 
    })

    // const deleteCategory = async(id:number) => {
    //     const confirmed = confirm("Are you sure you want to delete this slider?");
    //     if (!confirmed) {
    //         return; 
    //     }
    //     await categoryStore.deleteCategory(id);
    // }

</script>