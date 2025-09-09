<template> 
    <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
        <LoadingSpinner v-if="loading.isLoading('category')" />
        <FrontendCategoryHorizontalCategory v-else :title="gameCategory" :categories="categoryStore.categories" :link="'category/slot'"/>
    </div>

    <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
        <LoadingSpinner v-if="loading.isLoading('category')" />
        <div v-else class="grid grid-cols-12 gap-4">
            <div class="col-span-4 lg:col-span-2" 
                v-for="game in games" :key="game.id">
                <FrontendGameCard :game="game" class="w-full"/>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLoadingStore } from '~/stores/loading';
import { useCategoryStore } from '~/stores/category';
import type { Game } from '~/types/games';
import { useLocaleStore } from '~/stores/locale';

const localeStore = useLocaleStore();
const categoryStore = useCategoryStore();
const loading = useLoadingStore();

const categorySlug = useRoute().params.category;
const games = ref<Game[]>([]);
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
onMounted(async () => {
    if(!categoryStore.categories.length){
        await categoryStore.fetchCategories();
    }
    if (categorySlug) {
        games.value = await categoryStore.getGameByCategory(categorySlug as string) || [];
    }
});
</script>
 