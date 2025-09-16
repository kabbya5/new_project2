<template>
    <div class="flex flex-col bg-slate-200/60 dark:bg-gray-700 shadow-xl">
        <button @click="playGame(game.id)">
            <div class="w-full">
                <NuxtImg :src="game.thumbnail ? game.thumbnail :game.image_url" :alt="game.english_name" class="w-full h-auto" />
            </div>
        </button>

        <div class="flex flex-col p-2">
           <button @click="playGame(game.id)" class="capitalize text-left text-[10px] md:text-[14px] font-semibold">{{ game[name] }}</button>
            <NuxtLink v-if="game.provider" :to="`/category/${'provider'}/${game.provider.slug}`" 
                class="capitalize text-[10px] md:text-[14px] font-bold  text-green-400 dark:text-orange-600 mt-1"> {{ game.provider[name] }} </NuxtLink> 
            
            <!-- <div class="flex items-center flex-wrap mt-1">
                <NuxtLink v-for="category in getRandomCategories(game.categories, 2)" :to="`/category/${category.slug}`" 
                class="capitalize text-[10px] md:text-[14px] font-bold text-orange-600 dark:text-green-400 mr-2"> {{ category[name] }} </NuxtLink> 
            </div> -->
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';
import { useAuthStore } from '~/stores/auth';

const localeStore = useLocaleStore();
const {getUser} = useAuthStore();

const props = defineProps({
    game: Object
});

const name = computed(() => localeStore.getTranslate('name'))

const playGame = async(id:number) =>{
    if(!getUser()?.id){
        navigateTo('/login');
        return;
    } 
    try{
      const response = await useApiFetch('/game/play/' + id);
        if (response.status === 1 && response.launch_url) {
            window.location.href = response.launch_url;
        } else {
            alert(response.msg || 'Failed to launch game try another game')
        }
    }catch(error){
        alert(error);
    }
}

const getRandomCategories = (categories: any[], count: number) => {
  if (!categories || categories.length === 0) return [];
  
  const shuffled = [...categories].sort(() => Math.random() - 0.5);
  return shuffled.slice(0, count);
};

</script>
