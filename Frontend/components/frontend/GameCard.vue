<template>
    <div class="flex flex-col bg-slate-200/60 dark:bg-gray-700 shadow-xl">
        <NuxtLink :to="game.link">
            <div class="width-full">
                <img :src="game.thumbnail" :alt="game.english_name" class="w-full h-full">
            </div>
        </NuxtLink>

        <div class="flex flex-col p-2">
            <p class="capitalize text-[11px] lg:text-[14px] font-semibold">{{ game[name] }}</p>
            <NuxtLink v-if="game.provider" :to="`/category/${'provider'}/${game.provider.slug}`" 
                class="uppercase text-[12px] lg:text-[14px] font-bold  text-green-400 dark:text-orange-600 mt-1"> {{ game.provider[name] }} </NuxtLink> 
            
            <div class="flex items-center flex-wrap mt-1">
                <NuxtLink v-for="category in game.categories" :to="`/category/${category.slug}`" 
                class="uppercase text-[12px] font-bold text-orange-600 dark:text-green-400 mr-2"> {{ category[name] }} </NuxtLink> 
            </div>
        </div>
    </div>
</template>

<script setup>

import { useLocaleStore } from '~/stores/locale';

const localeStore = useLocaleStore();

const props = defineProps({
    game: Object
});

const name = computed(() => localeStore.getTranslate('name'))

</script>
