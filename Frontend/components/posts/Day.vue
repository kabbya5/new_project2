<template>
    <div class="bg-white mb-4">
        <CreateDay  v-if="isVisableDayForm" :closeForm="toggleVisiableDayForm" />
        <div class="swiper-wrapper">
          <ClientOnly>
            <swiper-container
            ref="swiperBasicRef"
              class="swiper-basic"
              :loop="true"
              :slidesPerView="5"
            > 
            
              <swiper-slide @click="toggleVisiableDayForm" class="w-[150px] h-[200px] relative mx-2 rounded-lg border-2 border-gray-400 overflow-hidden">
                  <img src="https://shorturl.at/Vx06F" alt="Post Image" class="w-full h-full object-cover"> 

                  <div class="absolute bottom-0 left-0 w-full bg-blue-800 text-white text-center py-2">
                      <h2 class="text-sm">Create Post</h2>
                  </div>
              </swiper-slide>

              <swiper-slide v-for="i in range" :key="i" class="group w-[150px] h-[200px] relative mx-2 rounded-lg border-2 border-gray-400 overflow-hidden">
                  <img src="https://shorturl.at/Vx06F" alt="Post Image" class="w-full h-full object-cover transform transition-transform duration-300 group-hover:scale-105"> 
                  <div class="absolute bottom-3 left-0 px-4 text-white font-[10px]">
                      Md Ibrahim 
                  </div>
                  <div class="absolute inset-0 bg-gray-500 opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
              </swiper-slide>
            </swiper-container>
            <div class="swiper-basic-buttons">
              <button @click="swiper1.prev()">
                <font-awesome :icon="['fas', 'arrow-left']" />
              </button>  
              <button @click="swiper1.next()"> 
                <font-awesome :icon="['fas','arrow-right']"/> 
              </button>
            </div>
          </ClientOnly>
      </div>
    </div>
</template>

<script setup lang="ts">
import CreateDay from './CreateDay.vue';

const  range = ref([1,2,3,4,5,6,7,8,9,10]);
const swiperBasicRef = ref(null)
const swiperCreativeRef = ref(null)
const currentIndex = ref(0) 
const swiper1 = useSwiper(swiperBasicRef)

const isVisableDayForm = ref(false);

const toggleVisiableDayForm = () => {
    isVisableDayForm.value = !isVisableDayForm.value;
};

useSwiper(swiperCreativeRef, {
  effect: 'creative',
  autoplay: {
    delay: 8000,
    disableOnInteraction: true,
  },
  creativeEffect: {
    prev: {
      translate: ['-125%', 0, -800],
      rotate: [0, 0, -90],
    },
    next: {
      translate: ['125%', 0, -800],
      rotate: [0, 0, 90],
    },
  },
})

watch(currentIndex, (newIndex) => {
    console.log('Current Slide Index:', newIndex)
})
</script>

<style>
  .swiper-wrapper{
    position: relative;
  }
  .swiper-basic-buttons{
      position: absolute;
      top: 50%;
      left: 50%;
      width: 100%;
      transform: translate(-50%, -50%);
      display: flex;
      justify-content: space-between;
      z-index: 40;
  }

  .swiper-basic-buttons button{
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: #e4e4e4;
    color: black;
  }
</style>