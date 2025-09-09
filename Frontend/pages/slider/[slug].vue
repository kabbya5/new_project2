
<template>
  <div class="bg-slate-200/60 dark:bg-gray-900">
    <div class="container mx-auto bg-white dark:bg-gray-800 p-3 lg:p-4">
        <div v-if="slider" v-html="slider.slider_content"></div>
        <div v-else>
            <p class="text-gray-500">Slider not found.</p>
        </div>
    </div>
  </div>
</template>


<script setup lang="ts">
import { useRoute } from 'vue-router'
import { useSliderStore } from '~/stores/sliderStore'

const route = useRoute()
const sliderStore = useSliderStore()

const slug = route.params.slug as string
const slider = ref<object|null>(null)


onMounted(async () => {
  // যদি store খালি থাকে, API থেকে fetch করো
  if (!sliderStore.sliders.length) {
    await sliderStore.fetchSliders()
  }

  // dynamic param অনুযায়ী slider খুঁজো
  const slug = route.params.slug as string
  slider.value = sliderStore.sliders.find(s => s.slug === slug)

})
</script>
