<template>
    <AdminGameForm v-if="isModalOpen" :gameId="currentId" @close="isModalOpen=false"/>

    <div class="bg-white dark:bg-gray-800 p-3">
        <div class="flex justify-between items-center">
            <div class="flex justify-between items-center">
                <div class="form-group">
                    <input type="text" v-model="search_game" placeholder="Search Game" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
                </div>

                <div class="form-group ml-2">
                    <select v-model="provider_id" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
                        <option value="" class="bg-white dark:bg-gray-700 text-gray-800 dark:text-white"> Select Provider </option>
                        <option v-for="provider in providerStore.providers" :value="provider.id" class="bg-white dark:bg-gray-700 text-gray-800 dark:text-white"> {{ provider.english_name }} </option>
                    </select>
                </div>

                <div class="form-group">
                    <button @click="search" class="primary-button px-3 ml-2 py-[6px] bg-blue-500 text-white rounded-md"> Search  </button>
                </div>
            </div>
      

        
            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-[6px] bg-sky-500 text-white rounded-md"> Add Game </button>
            </div>
        </div>

        <div class="my-5">
            <div class="overflow-x-auto w-full">
                <table class="divide-y table-fixed divide-gray-200 dark:divide-gray-700 rounded-lg min-w-full">
                    <thead class="bg-gray-100 dark:bg-gray-900">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Game Code
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            English Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Bangla Name
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Hindi Name
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Categories
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Provider
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Image
                        </th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="game in gameStore.games" :key="game.id">
                            <td class="px-6 py-2  text-sm text-gray-900 dark:text-gray-100">{{ game.game_code }}</td>
                            <td class="px-6 py-2 text-sm text-gray-900 dark:text-gray-100">{{ game.english_name }}</td>
                            <td class="px-6 py-2  text-sm text-gray-600 dark:text-gray-300">{{ game.bangla_name }}</td>
                            <td class="px-6 py-2 text-sm text-gray-600 dark:text-gray-300"> {{ game.hindi_name }}</td>
                            <td class="px-6 py-2  text-sm text-gray-600 dark:text-gray-300"> 
                                <span v-if="game.categories" v-for="category in game.categories" class="pr-2">
                                    {{ category.english_name }}
                                </span>
                            </td>
                            <td v-if="game.provider" class="px-6 py-4  text-sm text-gray-600 dark:text-gray-300"> 
                                {{ game.provider.english_name }}</td>
                            <td class="px-6 py-2  text-sm text-gray-600 dark:text-gray-300">
                                <NuxtImg :src="game.thumbnail ? game.thumbnail : game.image_url" :alt="game.english_name" class="w-7" />
                            </td>
                            <td class="px-6 py-2  text-right text-sm font-medium">
                                <button @click="createUpdateModal(game.id)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-edit"></i>
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <Pagination v-model="currentPage" :total-pages="totalPages" />
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useCategoryStore } from '~/stores/category';
    import { useProviderStore } from '~/stores/provider';
    import { useGameStore } from '~/stores/game';

    const categoryStore = useCategoryStore();
    const providerStore = useProviderStore();
    const gameStore = useGameStore();

    const currentPage = ref(gameStore.pagination?.current_page);
    const totalPages = computed(() => gameStore.pagination?.last_page ?? 1);
    const provider_id = ref<string>('');
    const search_game = ref<string>('');

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
        await categoryStore.fetchCategories();
        await providerStore.fetchProviders();
        
    });

    async function fetchPosts() {
        await gameStore.fetchGames(currentPage.value,
            48,
            search_game.value,
            provider_id.value || '',
        );
    }

    const search = (async() => {
        await gameStore.fetchGames(
            currentPage.value,
            48,
            search_game.value,
            provider_id.value || '',
        ) 
    })

    watch(currentPage, fetchPosts, { immediate: true })

</script>