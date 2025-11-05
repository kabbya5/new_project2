<template>
    <div class="bg-red-800 dark:bg-green-800">
        <section class="container mx-auto p-4">
            <h1 class="text-4xl font-bold text-center text-white">🎉 Current Promotions</h1>

            <div class="grid grid-cols-12 gap-4 mt-6">
                <div v-for="promotion in promotions" class="col-span-12 md:col-span-6 lg:col-span-4">
                    <div class="bg-red-900 dark:bg-green-900 text-white rounded-2xl shadow-md hover:shadow-xl transition duration-300 overflow-hidden">
                        <NuxtImg :src="promotion.image_url"
                            alt="VIP Level" class="w-full w-full max-h-48 object-cover"/>
                        <div class="p-2">
                            <h2 class="text-xl font-semibold text-white mb-2">{{ promotion.title }}</h2>
                            <div  v-html="promotion.content.slice(0,300)"></div>
                        </div>

                        <div class="p-2 mb-2">
                            <NuxtLink
                                :to="`/promotion/${promotion.slug}`"
                                class="inline-block bg-white text-red-900 font-semibold px-4 py-2 rounded-lg hover:bg-gray-200 transition"
                            >
                                Show More
                            </NuxtLink>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script setup lang="ts"> 
    const promotions = ref<any>();

    onMounted(async() =>{
        const response :any = await useApiFetch('/promotions');
        if(response.data){
            promotions.value = response.data;
        }
    });
</script>