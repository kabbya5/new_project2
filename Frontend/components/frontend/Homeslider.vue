<script setup lang="ts">
const containerRef = ref(null)
const slides = ref<{ image: string }[]>([
  { image: '/images/slider_image_227058.jpg' },
  { image: '/images/slider_image_227601.jpg' },
  { image: '/images/slider3.jpg' }
])

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
            v-for="(slide, index) in slides"
            :key="index"
            >
            <img :src="slide.image" class="w-full h-full min-h-[300px] object-cover" />
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