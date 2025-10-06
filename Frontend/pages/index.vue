
<template>
    <LoadingSpinner v-if="loading.isLoading('slider')" />
    <FrontendHomeslider v-else :sliders="sliderStore.sliders"> </FrontendHomeslider>
    <div class="my-4">
        <div class="bg-slate-200/60 dark:bg-gray-900">
            <div  class="container mx-auto bg-white dark:bg-gray-800 px-3 lg:px-4">
                <FrontendMovingText />
            </div>
        </div>
    </div>
    <!-- <div v-if="authStore.token">
        <div class="bg-slate-200/60 dark:bg-gray-900 md:hidden py-1">
            <div class="container mx-auto">
                <div class="flex items-center justify-center space-x-4">
                
                <NuxtLink
                    to="/profile/deposite"
                    class="px-6 py-1 rounded-xl text-white font-semibold shadow-lg 
                        bg-gradient-to-r from-green-500 to-green-600
                        hover:from-green-600 hover:to-green-700 
                        active:scale-95 transition-all duration-200
                        dark:from-green-400 dark:to-green-500"
                >
                    {{ deposite }}
                </NuxtLink>
                <NuxtLink
                    to="/profile/withdrow"
                    class="px-6 py-1 rounded-xl text-white font-semibold shadow-lg
                        bg-gradient-to-r from-red-500 to-red-600
                        hover:from-red-600 hover:to-red-700
                        active:scale-95 transition-all duration-200
                        dark:from-red-400 dark:to-red-500"
                >
                    {{withdrow}}
                </NuxtLink>
                
                </div>
            </div>
        </div>

    </div> -->

    <div>
        <div class="py-2 lg:py-4">
            <!-- Category  -->
            <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('category')" />
                <FrontendCategoryHorizontalCategory v-else :title="gameCategory" :categories="categoryStore.categories" :categorySlug="categoryStore.categories[0]?.slug"/>
            </div>
            
            <!-- Recently Play  -->
            <div v-if="authStore.getUser()?.id" class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('recentlyPlay')" />
                <div v-else class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 flex items-center justify-between">
                        <NuxtLink to="/game/recently_play" class="text-md lg:text-2xl font-bold border-b border-blue-500"> {{recentlyPlayTitle }} </NuxtLink>
                    </div>
                    <div class="col-span-4 lg:col-span-2" 
                        v-for="game in recentlyPlay.games" :key="game.id">
                        <FrontendGameCard :game="game" class="w-full"/>
                    </div>

                    <div class="col-span-12 flex items-center justify-center my-4">
                        <NuxtLink 
                        to="/game/recently_play"
                        class="inline-flex items-center gap-2 text-sm lg:text-lg font-semibold 
                                px-4 py-2 rounded-xl 
                                bg-gradient-to-r from-green-600 to-green-700 
                                hover:from-green-700 hover:to-green-800 
                                text-white shadow-md hover:shadow-lg 
                                transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                        <span>View All</span>
                        <i class="fa-solid fa-arrow-right text-base"></i>
                        </NuxtLink>
                    </div>
                </div>
            </div>

            <!-- Popular Game  -->

            <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
                <LoadingSpinner v-if="loading.isLoading('game')" />
                <div v-else class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 flex items-center justify-between">
                        <NuxtLink to="/game" class="text-md lg:text-2xl font-bold border-b border-blue-500"> {{popularGame }} </NuxtLink>
                    </div>
                    <div class="col-span-4 lg:col-span-2" 
                        v-for="game in gameStore.games" :key="game.id">
                        <FrontendGameCard :game="game" class="w-full"/>
                    </div>

                    <div class="col-span-12 flex items-center justify-center my-4">
                        <NuxtLink 
                        to="/game"
                        class="inline-flex items-center gap-2 text-sm lg:text-lg font-semibold 
                                px-4 py-2 rounded-xl 
                                bg-gradient-to-r from-green-600 to-green-700 
                                hover:from-green-700 hover:to-green-800 
                                text-white shadow-md hover:shadow-lg 
                                transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                        <span>View All</span>
                        <i class="fa-solid fa-arrow-right text-base"></i>
                        </NuxtLink>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLoadingStore } from '~/stores/loading';
import { useGameStore } from '~/stores/game';
import { useRecentlyPlay } from '~/stores/recentlyPlay';
import { useCategoryStore } from '~/stores/category';
import { useLocaleStore } from '~/stores/locale';
import { useSliderStore } from '~/stores/sliderStore';
import {useAuthStore} from '~/stores/auth';


const loading = useLoadingStore();
const gameStore = useGameStore();
const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();
const sliderStore = useSliderStore();
const authStore = useAuthStore();
const recentlyPlay = useRecentlyPlay();

const name = computed(() => localeStore.getTranslate('name'))
const recentlyPlayTitle = computed(() => localeStore.getTranslate('recentlyPlay'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
const popularGame = computed(() => localeStore.getTranslate('popularGame'))
const deposite = computed(() => localeStore.getTranslate('deposite'))
const withdrow = computed(() => localeStore.getTranslate('withdrow'))
const viewAll = computed(() => localeStore.getTranslate('viewAll'))

onMounted(async () => {
    if(!sliderStore.sliders.length){
        await sliderStore.fetchSliders();
    }

    if(!categoryStore.categories.length){
        await categoryStore.fetchCategories();
    } 

    if(authStore.getUser()?.id){
        await recentlyPlay.fetchGames();
    }
    
    gameStore.fetchGames();
    
    if(authStore.getUser()?.id){
        authStore.recallUser();
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