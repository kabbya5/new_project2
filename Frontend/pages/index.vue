
<template>
    <LoadingSpinner v-if="loading.isLoading('slider')" />
    <FrontendHomeslider v-else :sliders="sliderStore.sliders"> </FrontendHomeslider>
    <div>
        <div class="bg-slate-200/60 dark:bg-gray-900 py-2 lg:py-4">
            <!-- Provider  -->
            <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('provider')" />
                <FrontendCategoryHorizontalCategory v-else :title="gameProvider" :categories="providerStore.providers" :link="'category/providers/jili'"/>
            </div>

            <!-- Recently Play  -->
            <div v-if="getUser()?.id" class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('recentlyPlay')" />
                <FrontendGameSlider v-else :games="recentlyPlay.games" :title="recentlyPlayTitle" :link="'/resent/game'" />
            </div>

            <!-- Category  -->
            <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('category')" />
                <FrontendCategoryHorizontalCategory v-else :title="gameCategory" :categories="categoryStore.categories" :link="'category/slot'"/>
            </div>

            <!-- Popular Game  -->

            <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('game')" />
                <FrontendGameSlider v-else :games="gameStore.games" :title="popularGame" link="`/category/${category.slug}`" />
            </div>

            <!-- Catgory Game  -->

            <div v-for="category in categoryStore.categories" :key="category.id" class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4 min-h-[150px]">
                <LoadingSpinner v-if="loading.isLoading('category')" /> 
                <FrontendGameSlider :link="`/category/${category.slug}`"
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
import { useProviderStore } from '~/stores/provider';
import { useSliderStore } from '~/stores/sliderStore';
import {useAuthStore} from '~/stores/auth';
import { useRecentlyPlay } from '~/stores/recentlyPlay';

const loading = useLoadingStore();
const gameStore = useGameStore();
const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();
const providerStore = useProviderStore();
const sliderStore = useSliderStore();
const {getUser} = useAuthStore();
const recentlyPlay = useRecentlyPlay();

const name = computed(() => localeStore.getTranslate('name'))
const recentlyPlayTitle = computed(() => localeStore.getTranslate('recentlyPlay'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
const popularGame = computed(() => localeStore.getTranslate('popularGame'))
const gameProvider = computed(() => localeStore.getTranslate('gameProvider'))

onMounted(async () => {
    if(!sliderStore.sliders.length){
        await sliderStore.fetchSliders();
    }

    if(!providerStore.providers.length){
        await providerStore.fetchProviders();
    }
    
    if(!recentlyPlay.games.length && getUser()?.id){
       await recentlyPlay.fetchGames();
    }

    gameStore.fetchGames();

    if(!categoryStore.categories.length){
        await categoryStore.fetchCategories();
    } 
})

useHead({
  title: 'Luckbuzz99',
  meta: [
    { name: 'description', content: 'This is the homepage of my Nuxt app with SEO support.' },
    { name: 'keywords', content: 'Nuxt 3, SEO, Vue, JavaScript' },
    { property: 'og:title', content: 'Luckbuzz99' },
    { property: 'og:description', content: 'Welcome to my Nuxt app homepage with SEO best practices.' },
    { property: 'og:type', content: 'website' },
    { property: 'og:url', content: 'https://luckbuzz99.com/' },
    { property: 'og:image', content: 'https://mywebsite.com/cover.jpg' },
    { name: 'twitter:card', content: 'summary_large_image' },
    { name: 'twitter:title', content: 'Luckbuzz99' },
    { name: 'twitter:description', content: 'SEO friendly Nuxt app homepage.' },
    { name: 'twitter:image', content: 'https://mywebsite.com/cover.jpg' }
  ],
  link: [
    { rel: 'canonical', href: 'https://luckbuzz99.com/' }
  ]
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