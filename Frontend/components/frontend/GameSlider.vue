<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';
import { useAuthStore } from '~/stores/auth';

import type {Game} from '~/types/games';
const {getUser} = useAuthStore();

const localeStore = useLocaleStore();

const props = defineProps<{
    games: Game[],
    title: string,
    link: string,
}>()

const name = computed(() => localeStore.getTranslate('name'))

const containerRef = ref(null)

const swiper = useSwiper(containerRef, {
  effect: 'creative',
  loop: true,
  autoplay: {
    delay: 5000,
  },
  creativeEffect: {
    prev: {
      shadow: true,
      translate: [0, 0, -400],
    },
    next: {
      shadow: true,
      translate: [0, 0, -400],
    },
  },
})

const getRandomCategories = (categories: any[], count: number) => {
  if (!categories || categories.length === 0) return [];
  
  const shuffled = [...categories].sort(() => Math.random() - 0.5);
  return shuffled.slice(0, count);
};

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
</script>

<template>
  <ClientOnly>
    <div class="relative">
        <div class="flex justify-between align-items-center">
            <NuxtLink :to="link" class="text-md lg:text-2xl font-bold border-b border-blue-500"> {{ title }} </NuxtLink>
            <div class="swiper-basic-buttons">
                <button @click="swiper.prev()" class="px-2 lg:px-4 lg:py-1">
                    <i class="fa-solid fa-arrow-left text-[12px] lg:text-[16px]" /> 
                </button>  

                <button @click="swiper.next()" class="px-2 lg:px-4 lg:py-1"> 
                   <i class="fa-solid fa-arrow-right text-[12px] lg:text-[16px]" /> 
                </button>
            </div>
        </div>
        
      <!-- Swiper Container -->
      <swiper-container 
        class="mt-3"
        ref="containerRef"
        :loop="true"
        :slidesPerView="5"
        :space-between="10"
        :autoplay="{ delay: 3000 }"
        :breakpoints="{
            320: { slidesPerView: 3},
            600: { slidesPerView: 4 },
            900: { slidesPerView: 5 },
            1124: { slidesPerView: 6 },
            1600:{slidesPerView:8}
        }"
      >
        <swiper-slide
          v-for="(game, index) in games"
          :key="index"
        >
            <div class="flex flex-col">
                <button @click="playGame(game.id)">
                    <div class="width-full">
                        <img :src="game.thumbnail ? game.thumbnail : game.image_url" :alt="game.english_name" class="w-full h-[120px] lg:h-full">
                    </div>
                </button>

                <div class="flex flex-col p-2">
                  <button @click="playGame(game.id)" class="capitalize text-left text-[10px] md:text-[14px] font-semibold">{{ game[name] }} </button>
                  <NuxtLink v-if="game.provider" :to="`/category/${'provider'}/${game.provider.slug}`" 
                      class="uppercase text-[11px] md:text-[14px] font-bold  text-green-400 dark:text-orange-600 mt-1"> {{ game.provider[name] }} </NuxtLink> 
                  
                  <!-- <div class="flex items-center flex-wrap mt-1">
                      <NuxtLink v-for="category in getRandomCategories(game.categories, 2)" :to="`/category/${category.slug}`" 
                      class="uppercase text-[9px] md:text-[14px] font-bold text-orange-600 dark:text-green-400 mr-2"> {{ category[name] }} </NuxtLink> 
                  </div> -->
                </div>
            </div>
          
        </swiper-slide>

      </swiper-container>
    </div>
  </ClientOnly>
</template>

<style lang="css">
swiper-slide {
  display: flex;
  justify-content: center;
  align-items: center;
  font-size: 18px;
  font-size: 4rem;
  font-weight: bold;
  font-family: 'Roboto', sans-serif;
}

.swiper-basic-buttons button{
    background-color: red;
    margin-left: 10px;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
</style>