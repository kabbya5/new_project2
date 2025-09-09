<template>
    <div>
        <NuxtLink :to="link" class="flex items-center">
            <i class="fa-solid fa-gamepad text-xl lg:fa-2x"></i>
            <h2 class="pl-2 font-semibold text-md lg:text-2xl"> {{ title }}</h2>
        </NuxtLink>
        <div class="overflow-x-auto scrollbar-hide mt-3" style="scrollbar-width: 0;">
            <div class="flex space-x-2 lg:space-x-4">
                <NuxtLink style="min-width: fit-content;"
                    v-for="(category, index) in categories"
                    :key="index"
                    :to="category.image_url ? `/category/${category.slug}` : `/category/provider/${category.slug}`"
                    class="flex w-full items-center justify-center border border-slate-300 dark:border-slate-700 px-2 py-2 transition hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white"
                    active-class="text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold"
                    >
                    <img :src="category.image_url ?? category.logo" :alt="category.english_name" class="w-6 lg:w-10">
                    <h2 class="text-sm ml-2 lg:text-xl font-semibold capitalize mt-1">
                        {{ category[name] }}
                    </h2>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';
const localeStore = useLocaleStore();

const name = computed(() => localeStore.getTranslate('name'))

const props = defineProps<{
    categories:any,
    title: string,
    link: string,
}>()

</script>

<style scoped>
    .scrollbar-hide {
        scrollbar-width: none; /* Firefox */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
    }
</style>