<template>
    <div>
        <NuxtLink class="flex items-center">
            <i class="fa-solid fa-gamepad text-xl lg:text-4xl"></i>
            <h2 class="pl-2 text-md lg:text-2xl lg:-mt-1"> {{ title }}</h2>
        </NuxtLink>

        <div ref="categoryRef" class="overflow-x-auto scrollbar-hide mt-3" style="scrollbar-width: 0;">
            <div class="flex">
                <button style="min-width: fit-content;"
                    v-for="category, in categories"
                    :id="category.slug"
                    :key="category.id"
                    @click="openProvider(category.slug)"
                    class="flex flex-col w-full items-center justify-center px-3 py-3 transition"
                    :class="activeSlug === category.slug ? 'bg-card text-white' : ''"
                    >
                    <img :src="category.image_url ?? category.logo" :alt="category.english_name" class="w-8 lg:w-10">
                    <h2 class="text-[14px] lg:text-xl capitalize">
                        {{ category[name] }}
                    </h2>
                </button>
            </div>
        </div>

        <div v-if="isFixed" class="fixed top-7 md:top-11 my-1 left-0 z-50 w-full bg-card">
            <div class="overflow-x-auto scrollbar-hide pb-4 pt-6" style="scrollbar-width: 0;">
                <div class="flex space-x-2 lg:space-x-4">
                    <button v-if="fixedActiveSlug && fixedActiveSlug != 'false'" style="min-width: fit-content;"
                        @click="openFixedProvider('false')"
                            class="flex w-full items-center justify-center border-0 px-1 text-red-600"
                        >
                        
                        <h2 class="text-[14px] ml-2 lg:text-xl text-white capitalize">
                            X 
                        </h2>
                    </button>

                    <NuxtLink style="min-width: fit-content;"
                        v-for="category, in categories"
                        :id="category.slug"
                        :key="category.id"
                        :to="`/category/${category.slug}`"
                        
                        class="flex w-full items-center justify-center px-1 transition hover:text-green-900 "
                        :class="fixedActiveSlug === category.slug ? 'text-green-800  border-green-700' : 'text-gray-200'"
                        >
                        
                        <h2 class="text-[15px] ml-2 text-white lg:text-md capitalize">
                            {{ category[name] }}
                        </h2>
                    </NuxtLink>
                </div>
            </div>
        </div>

        <LoadingSpinner v-if="loading.isLoading('provider')" />
        <div v-else class="mt-1 gap-1 grid grid-cols-12 ">
            <div v-for="(provider, index) in providers" class="col-span-4 md:col-span-3 lg:col-span-2">
                <NuxtLink style="min-width: fit-content;"
                    :key="index"
                    class="flex flex-col bg-card md:flex-row w-full items-center justify-center px-1 py-2 transition hover:bg-red-700"
                    :to="`/category/${activeSlug}/${provider.slug}`"
                    >
                    <img :src="provider.logo" :alt="provider.english_name" class="w-auto h-[40px]">
                    <h2 class="text-[10px] text-capitalize md:ml-2 lg:text-[15px] capitalize mt-1">
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
const activeSlug = ref<string|null>(props.categorySlug)
const fixedActiveSlug = ref<string>('')


const updateSlugByDevice = () => {
  if (window.innerWidth < 640) {
    activeSlug.value = null
  } else {
    activeSlug.value = props.categorySlug
  }
}

const categoryRef = ref(null);
const isFixed = ref(false);

const handleScroll = () => {
  if (!categoryRef.value) isFixed.value = !isFixed.value;
  const offsetTop = categoryRef.value.offsetTop
  isFixed.value = window.scrollY >= offsetTop
};

onMounted(async() =>{

    updateSlugByDevice()

    window.addEventListener("resize", updateSlugByDevice)

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