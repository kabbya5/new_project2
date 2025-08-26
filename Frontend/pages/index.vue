
<template>
    <FrontendHomeslider> </FrontendHomeslider>
    <div>
        <div class="bg-slate-200/60 dark:bg-gray-900 py-2 lg:py-4">

            <!-- Recently Play  -->
            <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('game')" />
                <FrontendGameSlider v-else :games="gameStore.games" :title="recentlyPlay" />
            </div>

            <!-- Category  -->
            <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
                <FrontendCategoryHorizontalCategory :title="gameCategory"/>
            </div>

            <!-- Popular Game  -->

            <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('game')" />
                <FrontendGameSlider v-else :games="gameStore.games" :title="popularGame" />
            </div>

             <!-- Catgory Game  -->

            <div v-for="category in categoryStore.categories" :key="category.id" class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4 min-h-[150px]">
                <LoadingSpinner v-if="loading.isLoading('category')" /> 
                 <FrontendGameSlider
                    v-if="Array.isArray(category.games)"
                    :games="category.games"
                    :title="category[name]"
                />
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLoadingStore } from '~/stores/loading';
import { useGameStore } from '~/stores/game';
import { useCategoryStore } from '~/stores/category';
import { useLocaleStore } from '~/stores/locale';

const loading = useLoadingStore();
const gameStore = useGameStore();
const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();

const name = computed(() => localeStore.getTranslate('name'))
const recentlyPlay = computed(() => localeStore.getTranslate('recentlyPlay'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
const popularGame = computed(() => localeStore.getTranslate('popularGame'))

onMounted(async () => {
    await categoryStore.fetchCategories();
    gameStore.fetchGames();
})

</script>

<style scoped>
.game-item-sm{
    width: 100px;
    height: 100px;
    border-radius: 50%;
    margin-right: 20px;
}

</style>