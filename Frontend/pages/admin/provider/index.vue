<template>
    <AdminProviderForm v-if="isModalOpen" :providerId="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3">
        <div class="flex justify-between items-center">
            <div class="form-group">
                <input type="text" placeholder="Search Provider" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
            </div>

            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-1 bg-sky-500 text-white rounded-md"> Add Provider </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto">
                <LoadingSpinner v-if="loading.isLoading('provider')" />
                <table v-else class="min-w-full divide-y divide-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-card">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            provider ID
                        </th>
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
                            Categories
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                    <tr v-for="provider in providerStore.providers" :key="provider.id">
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ provider.provider_id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ provider.english_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">{{ provider.bangla_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"> {{ provider.hindi_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm"> 
                            <span v-if="provider.categories" v-for="category in provider.categories" class="pr-2">
                                {{ category.english_name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <img :src="provider.logo" :alt="provider.english_name" class="w-7">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button @click="createUpdateModal(provider.id)" class="">
                                <i class="fa-solid fa-edit"></i>
                            </button>
                        </td>
                    </tr>
                    <!-- Repeat more rows as needed -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

    import { useCategoryStore } from '~/stores/category';
    import { useProviderStore } from '~/stores/provider';
    import { useLoadingStore } from '~/stores/loading';

    const categoryStore = useCategoryStore();
    const providerStore = useProviderStore();
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
        if(providerStore.providers.length === 0){
            await providerStore.fetchProviders();
        }

        if(categoryStore.categories.length === 0){
            await categoryStore.fetchCategories();
        }
    })

</script>