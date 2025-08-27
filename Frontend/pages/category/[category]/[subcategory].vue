<template> 
    <div class="bg-slate-200/60 dark:bg-gray-900 py-4">
        <div class="container mx-auto bg-white dark:bg-gray-800  p-3 lg:p-4">
            <div class="flex items-center">
                <NuxtLink to="/" class="text-sm uppercase"> Home <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/category/${category}`" class="uppercase text-sm"> {{ 'category' }} <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/category/${category}/${providerSlug}`" class="uppercase text-sm text-red-500"> {{ providerSlug }} </NuxtLink>
            </div>

            <div class="mt-3">
                <div class="flex flex-wrap justify-start gap-2">
                    <NuxtLink v-for="provider in providerStore.providers" :key="provider.id"
                       :to="`/category/${category}/${provider.slug}`" class="rounded-md  mb-2 py-1 px-2 md:py-2 px-4  border border-gray-300 
                        dark:border-gray-900 transaction duration-300 hover:bg-red-500 hover:text-white"
                        active-class="text-white bg-red-500">
                        <div class="flex items-center justify-center">
                            <img :src="provider.logo" alt="image" class="w-[20px] lg:w-[25px]">

                            <p class="ml-2 text-sm -mt-1 lg:text-lg">{{ provider[name] }}</p>
                        </div>
                    </NuxtLink>
                </div>
            </div>
        </div>

        <div class="container mx-auto my-4 bg-white dark:bg-gray-800 p-3 lg:p-4">
        <LoadingSpinner v-if="loading.isLoading('game')" />
            <div class="flex flex-wrap justify-between gap-2">
                <div class="w-[120px] lg:w-[150px] mb-2 lg:mb-4" 
                    v-for="game in games" :key="game.id">
                    <FrontendGameCard v-if="game" :game="game"/>
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

const providerStore = useProviderStore();
const loading = useLoadingStore();
const localeStore = useLocaleStore();

const route = useRoute()
const category = route.params.category
const providerSlug = route.params.subcategory

const games = ref<Game[]>([]);
const name = computed(() => localeStore.getTranslate('name'))

onMounted(async() => {
  await providerStore.fetchProviders();

  if (providerSlug) {
    games.value = await providerStore.getGameByProvider(providerSlug as string) || [];
  }

  console.log('games', games.value);
});


</script>