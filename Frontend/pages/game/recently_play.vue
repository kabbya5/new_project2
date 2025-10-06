<template> 
    <div class="bg-slate-200/60 dark:bg-gray-900 py-4">
        <div class="container mx-auto bg-white dark:bg-gray-800  p-3 lg:p-4">
            <div class="flex items-center">
                <NuxtLink to="/" class="text-sm uppercase"> Home <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/game`" class="uppercase text-sm"> Game <i class="fa-solid fa-chevron-right text-[13px] mr-1"></i> </NuxtLink>
                <NuxtLink :to="`/game/recently_play`" class="uppercase text-sm text-red-500"> Recently Play </NuxtLink>
            </div>
        </div>

        <div class="container mx-auto my-1 bg-white dark:bg-gray-800 p-3 lg:p-4">
           
            <LoadingSpinner v-if="loading.isLoading('recentlyPlay')" />
            <div v-else class="grid grid-cols-12 gap-4">
                <div class="col-span-12 flex items-center justify-between">
                    <NuxtLink to="/game/recently_play" class="text-md lg:text-2xl font-bold"> {{recentlyPlayTitle }} </NuxtLink>
                </div>
                <div class="col-span-4 lg:col-span-2" 
                    v-for="game in recentlyPlay.games" :key="game.id">
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

import { useRecentlyPlay } from '~/stores/recentlyPlay';
import { useLocaleStore } from '~/stores/locale';

const loading = useLoadingStore();
const localeStore = useLocaleStore();
const recentlyPlay = useRecentlyPlay();

const name = computed(() => localeStore.getTranslate('name'))
const recentlyPlayTitle = computed(() => localeStore.getTranslate('recentlyPlay'))

onMounted(async() => {
   await recentlyPlay.fetchGames(1,200);
});

</script>