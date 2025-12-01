<template> 
    <div class="py-4">
        <div class="container mx-auto bg-white dark:bg-gray-800  p-3 lg:p-4">
            <div class="flex items-center">
                <NuxtLink to="/" class="text-sm uppercase"> Home <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/game/recently_play`" class="uppercase text-sm text-red-500"> Game </NuxtLink>
            </div>
        </div>

        <div class="container mx-auto my-1 bg-white dark:bg-gray-800 p-3 lg:p-4">
            <LoadingSpinner v-if="loading.isLoading('category')" />
            <FrontendCategoryHorizontalCategory v-else :title="gameCategory" :categories="categoryStore.categories" :categorySlug="categorySlug"/>

            <LoadingSpinner v-if="loading.isLoading('recentlyPlay')" />
            <div v-else class="grid grid-cols-12 gap-4">
                <div class="col-span-4 lg:col-span-2" 
                    v-for="game in gameStore.games" :key="game.id">
                    <FrontendGameCard :game="game" class="w-full"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

definePageMeta({
    middleware: 'auth'
});


import { useGameStore } from '~/stores/game';
import { useLocaleStore } from '~/stores/locale';
import { useCategoryStore } from '~/stores/category';

const loading = useLoadingStore();
const localeStore = useLocaleStore();
const gameStore = useGameStore();
const categoryStore = useCategoryStore();

const name = computed(() => localeStore.getTranslate('name'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))

onMounted(async() => {
   gameStore.fetchGames(1,1000);
    if(!categoryStore.categories.length){
        await categoryStore.fetchCategories();
    } 
});

</script>