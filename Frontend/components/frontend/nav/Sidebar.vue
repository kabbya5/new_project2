<template>
    <div class="fixed top-0 right-0 h-screen w-[250px] bg-gray-800 text-white shadow-xl z-50" style="overflow: auto;">
        <button @click="emit('close')"  class="absolute top-1 right-1 w-[30px] h-[30px] rounded-full bg-red-500">
            <i class="fa-solid fa-xmark"></i>
        </button> 
        
        <div class="px-3 py-2" @click="emit('close')">
            <h2 class="text-md"> {{ gameCategory }}</h2>

            <div class="mt-3 flex flex-wrap">
                <div class=" p-[3px]" 
                    v-for="(category, index) in categoryStore.categories"
                    :key="index">

                    <NuxtLink 
                        :to="`/category/${category.slug}`" 
                        class="border-2 border-slate-200 flex flex-col lg:flex-row xl:flex-col items-center justify-center
                            border px-2 py-1
                            border-slate-300 dark:border-slate-700 transition duration-300 
                            hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white" 
                        active-class="text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold"
                    >
                        <img :src="category.image_url" :alt="category.englist_name" class="w-7">

                        <h2 class="text-[13px] capitalize font-semibold lg:ml-2">
                            {{ category[name] }}
                        </h2>
                    </NuxtLink>
                </div>
            </div>
        </div>

        <div class="px-3 py-1" @click="emit('close')">
            <h2 class="text-md"> {{ gameProvider }}</h2>

            <div class="flex flex-wrap justify-start gap-2 pt-3">
                <NuxtLink v-for="provider in providerStore.providers" :key="provider.id"
                    :to="`/category/${category}/${provider.slug}`" class="rounded-md  mb-2 py-1 px-1 md:py-2 px-4  border border-gray-300 
                    dark:border-gray-900 transaction duration-300 hover:bg-red-500 hover:text-white"
                    active-class="text-white bg-red-500">
                    <div class="flex items-center justify-center">
                        <img :src="provider.logo" alt="image" class="w-[20px] lg:w-[25px]">

                        <p class="ml-2 capitalize text-[14px] -mt-1">{{ provider[name] }}</p>
                    </div>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

const emit = defineEmits(["close"])

import { useLocaleStore } from '~/stores/locale';
import { useCategoryStore } from '~/stores/category';
import { useProviderStore } from '~/stores/provider';

const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();
const providerStore = useProviderStore();

const name = computed(() => localeStore.getTranslate('name'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
const gameProvider = computed(() => localeStore.getTranslate('gameProvider'))

onMounted(async () => {
    await categoryStore.fetchCategories();
    await providerStore.fetchProviders();
})

</script>