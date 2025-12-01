<template> 
    <div class="pt-4">
        <div class="container mx-auto p-3 lg:p-4 mt-">
            <LoadingSpinner v-if="loading.isLoading('category')" />
            <FrontendCategoryHorizontalCategory v-else :title="gameCategory" :categories="categoryStore.categories" :categorySlug="categorySlug"/>
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
 