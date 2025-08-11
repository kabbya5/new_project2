<template>
    <div>
        <div class="flex items-center">
            <i class="fa-solid fa-gamepad fa-2x"></i>
            <h2 class="pl-4 font-semibold text-2xl"> {{ gameCategory }}</h2>
        </div>

        <div class="mt-3 flex flex-wrap">
            <div class=" p-[3px]" 
                v-for="(category, index) in categoryStore.categories"
                :key="index">

                <NuxtLink 
                    :to="`/category/${category.slug}`" 
                    class="border-2 border-slate-200 flex flex-col lg:flex-row xl:flex-col items-center justify-center
                        border px-4 py-1
                        border-slate-300 dark:border-slate-700 transition duration-300 
                        hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white" 
                    active-class="text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold"
                >
                    <img :src="category.image_url" :alt="category.englist_name" class="w-10">

                    <h2 class="text-md capitalize lg:text-xl font-semibold lg:ml-3 xl:ml-0">
                        {{ category[name] }}
                    </h2>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useCategoryStore } from '~/stores/category';
const useCategory = useCategoryStore(); 
import { useLocaleStore } from '~/stores/locale';

const loading = useLoadingStore();
const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();

const name = computed(() => localeStore.getTranslate('name'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))

const props = defineProps({
    title: String,
});

onMounted(async () => {
    await categoryStore.fetchCategories();
})

</script>