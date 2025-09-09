<script setup lang="ts">
import type { Slider } from '~/types/slider';
const props = defineProps<{
  sliders: Slider[];
}>();

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
      <!-- Swiper Container -->
      <swiper-container
        ref="containerRef"
        :loop="true"
        :init="false"
        :autoplay="{ delay: 3000 }"
        effect="creative"
        :pagination="true"
        :navigation="true"
        :creative-effect="{
          prev: { shadow: true, translate: [0, 0, -400] },
          next: { shadow: true, translate: [0, 0, -400] }
        }"
      >
        <swiper-slide
            v-for="(slider, index) in props.sliders"
            :key="index"
            >
            <NuxtLink :to="`/slider/${slider.slug}`">
              <img :src="slider.mobile_image" class="w-full md:hidden h-full min-h-[300px] object-cover" />
              <img :src="slider.desktop_image" class="w-full hidden md:block h-full min-h-[300px] object-cover" />
            </NuxtLink>
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
</style>