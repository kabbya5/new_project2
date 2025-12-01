<template>
    <div class="fixed inset-0 bg-black/50 z-100" @click="$emit('close')">
        <div class="w-fit side-menu shadow-xl z-100" style="overflow: auto;" @click.stop>
            <div class="flex overflow-auto h-screen">
                <ul class="w-[180px] ">
                    <li v-for="(item,index) in navItems" :key="index" class="py-3 px-4 border-b border-gray-600 w-full">
                        <NuxtLink :to="item.link" class="flex items-center" @click="$emit('close')">
                           <i class="ml-1" :class="item.icon"></i>  <p class="ml-3"> {{ item[name] }} </p>
                        </NuxtLink>
                    </li>

                    <li v-for="(category, index) in categoryStore.categories" :key="index"
                        class="py-3 px-4 border-b border-gray-600 w-full" :class="category.slug == openCategorySlug ? 'bg-gray-700' : ''">
                        <button @click="categoryProvider(category.slug)" class="flex items-center">
                           <NuxtImg :src="category.image_url" class="mr-3 w-8 h-auto" />  {{ category[name] }}
                         </button>
                    </li>

                    <li class="py-3 mb-6 px-4 border-b border-gray-600">
                        <LangToggler class="w-[80px] border-none text-md" />
                    </li>
                </ul>

                <ul v-if="openCategorySlug" class="w-[110px] bg-card border-l border-gray-600 overflow-auto">
                    <li v-for="(provider, index) in providers" :key="index"
                        class="py-3 px-4 border-b bg-card w-full">
                        <NuxtLink  :to="`/category/${openCategorySlug}/${provider.slug}`"  class="flex flex-col justify-center items-center">
                           <NuxtImg :src="provider.logo" class="w-auto h-[40px]" />  
                           <p class="text-[14px]"> {{ provider[name] }} </p>
                        </NuxtLink>
                    </li>
                </ul>
                
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

const emit = defineEmits(["close"])

import { useLocaleStore } from '~/stores/locale';
import { useCategoryStore } from '~/stores/category';
import { useProviderStore } from '~/stores/provider';
import {useAuthStore} from '~/stores/auth';

import type { Provider } from '~/types/provider';

const categoryStore = useCategoryStore();
const localeStore = useLocaleStore();
const providerStore = useProviderStore();
const authStore = useAuthStore();


const name = computed(() => localeStore.getTranslate('name'))
const gameCategory = computed(() => localeStore.getTranslate('gameCategory'))
const gameProvider = computed(() => localeStore.getTranslate('gameProvider'))

const openCategorySlug = ref<null|string>(null)
const providers = ref<Provider | null>(null)

const navItems = [
    {
        english_name: 'Home',
        bangla_name: 'হোম',
        hindi_name: 'होम',
        link: '/',
        icon:'fa-solid fa-home',
    },

    {
        english_name: 'Profile',
        bangla_name: 'প্রোফাইল',
        hindi_name: 'प्रोफ़ाइल',
        link: '/profile',
        icon: 'fa-solid fa-user-nurse'
    },

    {
        english_name: 'VIP Level',
        bangla_name: 'ভিআইপি লেভেল',
        hindi_name: 'वीआईपी स्तर',
        link: '/profile/level',
        icon: 'fa-solid fa-crown'
    },
    {
        english_name: 'Promotion',
        bangla_name: 'প্রমোশন',
        hindi_name: 'प्रमोशन',
        link: '/promotion',
        icon: 'fa-solid fa-bullhorn'
    },

    {
        english_name: 'Affiliate',
        bangla_name: 'অ্যাফিলিয়েট',
        hindi_name: 'सहयोगी',
        link: '/promotion/affiliate',
        icon: 'fa-solid fa-handshake'
    },
];

const logoutNav = {
  english_name: 'Logout',
  bangla_name: 'লগআউট',
  hindi_name: 'लॉगआउट',
};

onMounted(async () => {
    if(!categoryStore.categories.length){
        await categoryStore.fetchCategories();
    } 
})

const categoryProvider = async(slug:string) =>{
    providers.value = await providerStore.findProviderByCategory(slug);
    openCategorySlug.value = slug;
}
</script>