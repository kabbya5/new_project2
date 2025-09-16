<template> 
    <div class="bg-slate-200/60 dark:bg-gray-900 py-4">
        <div class="container mx-auto bg-white dark:bg-gray-800  p-3 lg:p-4">
            <div class="flex items-center">
                <NuxtLink to="/" class="text-sm uppercase"> Home <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/category/${category}`" class="uppercase text-sm"> {{ category}} <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/category/${category}/${providerSlug}`" class="uppercase text-sm text-red-500"> {{ providerSlug }} </NuxtLink>
            </div>

            <div class="mt-3">
                <div class="overflow-x-auto scrollbar-hide mt-3" style="scrollbar-width: 0;">
                    <div class="flex space-x-2 lg:space-x-4">
                       <NuxtLink v-for="provider in providers" :key="provider.id" style="min-width: fit-content;"
                            :to="`/category/${category}/${provider.slug}`" :id="provider.slug"
                            class="flex w-full items-center justify-center border border-slate-300 dark:border-slate-700 px-2 py-2 transition hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white"
                            active-class="text-white bg-red-500">
                            <img :src="provider.logo" alt="image" class="w-[20px] lg:w-[25px]">
                            <h2 class="text-sm ml-2 lg:text-xl font-semibold capitalize">
                                {{ provider[name] }}
                            </h2>
                        </NuxtLink>
                    </div>
                </div> 
            </div>
        </div>

        <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
            <LoadingSpinner v-if="loading.isLoading('games')" />
            <div v-else class="grid grid-cols-12 gap-2 md:gap-4">
                <div class="col-span-4 md:col-span-3 lg:col-span-2" 
                    v-for="game in games" :key="game.id">
                    <FrontendGameCard :game="game" class="w-full h-full"/>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLoadingStore } from '~/stores/loading';
import { useProviderStore } from '~/stores/provider';
import { useLocaleStore } from '~/stores/locale';

import type { Game } from '~/types/games'; 
import type { Provider } from '~/types/provider';

const providerStore = useProviderStore();
const loading = useLoadingStore();
const localeStore = useLocaleStore();

const route = useRoute()
const category = route.params.category
const providerSlug = route.params.subcategory;


const providers = ref<Provider | null>(null)
const games = ref<Game[]>([]);
const name = computed(() => localeStore.getTranslate('name'))

onMounted(async() => {
    if(!providerStore.providers.length){
        await providerStore.fetchProviders();
    }
    
    providers.value = await providerStore.findProviderByCategory(category) || [];

    if (providerSlug) {
        games.value = await providerStore.getGameByProvider(providerSlug) || [];
        scrollToCategory(providerSlug)
    }
});


function scrollToCategory(slug:string) {

  nextTick(() => {
    const el = document.getElementById(slug);
    if (el) {
      el.scrollIntoView({
        behavior: 'smooth',
        inline: 'center', 
        block: "nearest",
      });
    }
  });
}


</script>

<style scoped>
    .scrollbar-hide {
        scrollbar-width: none; /* Firefox */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
    }
</style>