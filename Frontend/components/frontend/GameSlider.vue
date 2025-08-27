<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';

import type {Game} from '~/types/games';

const localeStore = useLocaleStore();

const props = defineProps<{
    games: Game[],
    title: string,
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

</script>

<template>
  <ClientOnly>
    <div class="relative">
        <div class="flex justify-between align-items-center">
            <h2 class="text-2xl font-bold"> {{ title }} </h2>
            <div class="swiper-basic-buttons">
                <button @click="swiper.prev()">
                    <i class="fa-solid fa-arrow-left" /> 
                </button>  

                <button @click="swiper.next()"> 
                   <i class="fa-solid fa-arrow-right" /> 
                </button>
            </div>
        </div>
        
      <!-- Swiper Container -->
      <swiper-container 
        class="mt-3"
        ref="containerRef"
        :loop="true"
        :slidesPerView="5"
        :space-between="20"
        :autoplay="{ delay: 3000 }"
        :breakpoints="{
            320: { slidesPerView: 2},
            400: { slidesPerView: 4},
            600: { slidesPerView: 3 },
            900: { slidesPerView: 4 },
            1124: { slidesPerView: 6 },
            1600:{slidesPerView:8}
        }"
      >
        <swiper-slide
          v-for="(game, index) in games"
          :key="index"
        >
            <div class="flex flex-col">
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
    padding: 4px 10px;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
</style>