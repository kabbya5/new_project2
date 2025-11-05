<template>
  <section class="max-w-4xl mx-auto px-4 py-12">
    <NuxtImg
      :src="promotion?.image_url"
      alt="promotion.title"
      class="w-full rounded-2xl max-h-96 object-cover mb-6"
    />
    <h1 class="text-3xl font-bold mb-4 text-white">{{ promotion?.title }}</h1>
    <div class="text-white space-y-4" v-html="promotion?.content"></div>
    <NuxtLink to="/promotion" class="inline-block mt-6 text-white font-semibold hover:underline">
      ← Back to Promotions
    </NuxtLink>
  </section>
</template>

<script setup lang="ts">
const route = useRoute()
const slug = route.params.slug

const promotion = ref<any>();

onMounted(async() => {
    const response :any = await useApiFetch('/promotions/'+ slug);
    if(response.data){
        promotion.value = response.data;
    }
})
</script>
