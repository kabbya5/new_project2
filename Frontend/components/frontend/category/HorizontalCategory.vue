<template>
    <div>
        <NuxtLink class="flex items-center">
            <i class="fa-solid fa-gamepad text-xl lg:text-4xl"></i>
            <h2 class="pl-2 font-semibold text-md lg:text-2xl lg:-mt-1"> {{ title }}</h2>
        </NuxtLink>
        <div ref="categoryRef" class="overflow-x-auto scrollbar-hide mt-3" style="scrollbar-width: 0;">
            <div class="flex space-x-2 lg:space-x-4">
                <button style="min-width: fit-content;"
                    v-for="category, in categories"
                    :id="category.slug"
                    :key="category.id"
                    @click="openProvider(category.slug)"
                    class="flex w-full items-center justify-center border border-slate-300 dark:border-slate-700 px-2 py-2 transition hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white"
                    :class="activeSlug === category.slug ? 'text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold' : ''"
                    >
                    <img :src="category.image_url ?? category.logo" :alt="category.english_name" class="w-8 lg:w-10">
                    <h2 class="text-[16px] ml-2 lg:text-xl font-semibold capitalize">
                        {{ category[name] }}
                    </h2>
                </button>
            </div>
        </div>

        <div v-if="isFixed" class="fixed top-10 md:top-14 left-0 z-50 w-full bg-white dark:bg-black border-t border-slate-300 dark:border-slate-700">
            <div class="overflow-x-auto scrollbar-hide my-1 p-1" style="scrollbar-width: 0;">
                <div class="flex space-x-2 lg:space-x-4">
                    <button v-if="fixedActiveSlug && fixedActiveSlug != 'false'" style="min-width: fit-content;"
                        @click="openFixedProvider('false')"
                            class="flex w-full items-center justify-center border-0 px-1 py-1 text-red-600"
                        >
                        
                        <h2 class="text-[16px] ml-2 lg:text-xl font-semibold capitalize">
                            X 
                        </h2>
                    </button>

                    <button style="min-width: fit-content;"
                        v-for="category, in categories"
                        :id="category.slug"
                        :key="category.id"
                        @click="openFixedProvider(category.slug)"
                        class="flex w-full items-center justify-center text-gray-700 dark:text-gray-200 border-0 px-1 py-1 transition hover:text-green-900 "
                        :class="fixedActiveSlug === category.slug ? 'text-green-800 font-bold border-green-700' : 'text-gray-200'"
                        >
                        
                        <h2 class="text-[15px] ml-2 text-gray-700 dark:text-gray-200 lg:text-md font-semibold capitalize">
                            {{ category[name] }}
                        </h2>
                    </button>
                </div>
            </div>
            <LoadingSpinner v-if="loading.isLoading('provider')" />
                <div v-else class="my-2 grid grid-cols-12 border-t border-slate-300 dark:border-slate-700 pt-1 px-2">
                    <div v-for="(provider, index) in fixedProviders" class="col-span-4 md:col-span-3 lg:col-span-2">
                        <NuxtLink style="min-width: fit-content;"
                            :key="index"
                            class="flex flex-col md:flex-row w-full items-center justify-center border border-slate-300 dark:border-slate-700 px-2 py-2 transition hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white"
                            active-class="text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold"
                            :to="`/category/${activeSlug}/${provider.slug}`"
                            >
                            <!-- <img :src="provider.logo" :alt="provider.english_name" class="w-auto h-[40px]"> -->
                            <h2 class="text-[10px] text-capitalize md:ml-2 lg:text-md font-semibold capitalize">
                                {{ provider[name] }}
                            </h2>
                        </NuxtLink>
                    </div>
                </div>
        </div>

        <LoadingSpinner v-if="loading.isLoading('provider')" />
        <div v-else class="mt-3 grid grid-cols-12">
            <div v-for="(provider, index) in providers" class="col-span-4 md:col-span-3 lg:col-span-2">
                <NuxtLink style="min-width: fit-content;"
                    :key="index"
                    class="flex flex-col md:flex-row w-full items-center justify-center border border-slate-300 dark:border-slate-700 px-2 py-2 transition hover:bg-gray-300 hover:dark:bg-gray-900 hover:dark:text-white"
                    active-class="text-gray-600 bg-gray-300 dark:text-white dark:bg-gray-900 font-bold"
                    :to="`/category/${activeSlug}/${provider.slug}`"
                    >
                    <img :src="provider.logo" :alt="provider.english_name" class="w-auto h-[40px]">
                    <h2 class="text-[10px] text-capitalize md:ml-2 lg:text-[15px] font-semibold capitalize mt-1">
                        {{ provider[name] }}
                    </h2>
                </NuxtLink>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { useLoadingStore } from '~/stores/loading';
import { useLocaleStore } from '~/stores/locale';
import type { Provider } from '~/types/provider';
import {useProviderStore} from '~/stores/provider';

const loading = useLoadingStore();
const localeStore = useLocaleStore();
const providerStore = useProviderStore();

const name = computed(() => localeStore.getTranslate('name'))

const props = defineProps<{
    categories:any,
    title: string,
    categorySlug: string,
}>()

const providers = ref<Provider | null>(null)
const fixedProviders = ref<Provider | null>(null)
const activeSlug = ref<string>(props.categorySlug)
const fixedActiveSlug = ref<string>('')

const categoryRef = ref(null);
const isFixed = ref(false);

const handleScroll = () => {
  if (!categoryRef.value) isFixed.value = !isFixed.value;
  const offsetTop = categoryRef.value.offsetTop
  isFixed.value = window.scrollY >= offsetTop
};

onMounted(async() =>{
    if (props.categories.length > 0) {
        providers.value = await providerStore.findProviderByCategory(activeSlug.value);
    }
    scrollToCategory(activeSlug.value)

    window.addEventListener("scroll", handleScroll);
})

const openProvider = async(slug:string) =>{
   providers.value = await providerStore.findProviderByCategory(slug);
   activeSlug.value = slug;
}

const openFixedProvider= async(slug:string) =>{
   fixedProviders.value = await providerStore.findProviderByCategory(slug);
   fixedActiveSlug.value = slug;
}

function scrollToCategory(slug:string) {

  nextTick(() => {
    const el = document.getElementById(slug);
    if (el) {
      el.scrollIntoView({
        behavior: 'smooth',
        inline: 'center', 
        block: "nearest",
      });
    }
  });
}


</script>

<style scoped>
    .scrollbar-hide {
        scrollbar-width: none; /* Firefox */
    }

    .scrollbar-hide::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Edge */
    }
</style>