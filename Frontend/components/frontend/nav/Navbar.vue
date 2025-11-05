<template>
  <div id="navbar" class="fixed top-0 w-full z-100 bg-white dark:bg-black border border-gray-300 dark:border-gray-600 shadow-md dark:shadow-lg">
    <nav class="py-2 rounded container mx-auto">
      <div class="hidden md:flex justify-between items-center">
        <div class="flex-shrink-0 flex items-center px-2">
         
          <button @click="toggleSidebar = !toggleSidebar" class="text-gray-900 dark:text-gray-100 hover:text-indigo-500 focus:outline-none">
            <i class="fa-solid fa-bars text-[25px]"></i>
          </button>
            
          <NuxtLink to="/" class="ml-10 -mt-1">
             <NuxtImg class="w-[90px]" src="logo.png" />
          </NuxtLink>
        </div>

        
        <div>
          <ul class="nav-items flex items-center space-x-4">
            <li class="nav-link" v-for="(item,index) in navItems" :key="index">
              <NuxtLink :to="item.link" class="nav-item text-gray-800 dark:text-gray-100">{{ item[name] }}</NuxtLink>
            </li>

            <li v-if="authStore.getUser()?.role == 'admin'" class="nav-link" v-for="(item,index) in navItems" :key="index">
              <NuxtLink :to="'/admin/dashboard'" class="nav-item text-gray-800 dark:text-gray-100">Dashboard</NuxtLink>
            </li>

            <li v-if="authStore.getUser()?.role == 'agent'" class="nav-link">
              <NuxtLink :to="'/agent/dashboard'" class="nav-item text-gray-800 dark:text-gray-100">Dashboard</NuxtLink>
            </li>

            <li v-if="authStore.getUser()?.role == 'affiliate'" class="nav-link">
              <NuxtLink :to="'/affiliate/dashboard'" class="nav-item text-gray-800 dark:text-gray-100"> Dashboard </NuxtLink>
            </li>

            <li v-if="!authStore.token" class="nav-link" v-for="(item,index) in responsiveNavItems" :key="index">
              <NuxtLink :to="item.link" class="nav-item text-gray-800 dark:text-gray-100">{{ item[name] }}</NuxtLink>
            </li>

            <li v-else class="nav-link" v-if="authStore.getUser()?.name">
              <p class="text-gray-700 dark:text-white"> 
                <i class="fa-solid fa-user"></i>
                <span class="uppper ml-2 uppercase"> {{authStore.getUser()?.currency }} {{authStore.user?.balance }}</span>
              </p>
            </li>

            <li class="nav-link">
              <FrontendMessenger />
            </li>

            <li class="nav-link"><LangToggler /></li>

            <li> 
              <ThemToggler />
            </li>

            <li> 
              <NotificationToggler />
            </li>
            
          </ul>
        </div>
      </div>

      <div class="px-3 flex justify-between md:hidden items-center">
          <ul class="nav-items flex items-center justify-between space-x-4 w-full">
            <li> 
              <button @click="toggleSidebar = !toggleSidebar" class="text-gray-900 dark:text-gray-100 hover:text-indigo-500 focus:outline-none">
                <i class="fa-solid fa-bars text-[16px]"></i>
              </button>
            </li>

            <li class="nav-link">
              <NuxtLink to="/">
                <NuxtImg class="w-[90px]" src="logo.png" />
              </NuxtLink>
            </li>

            <li>
              <ul class="flex space-x-2">
                <li class="nav-link" v-if="authStore.getUser()?.name">
                  <p class="text-gray-700 text-sm dark:text-white"> 
                    <i class="fa-solid fa-user"></i>
                    <span class="uppper ml-2 uppercase"> {{authStore.getUser()?.currency }} {{userBalance}}</span>
                  </p>
                </li>
                <li class="nav-link"><ThemToggler /></li>
                <li class="nav-link">
                  <FrontendMessenger />
                </li>
              </ul>
            </li>
          </ul>
        </div>
    </nav>
  </div>

  <!-- sidebar  -->

  <FrontendNavSidebar v-if="toggleSidebar"  @close="toggleSidebar = false" />

</template>

<script setup lang="ts">
import NotificationToggler from './NotificationToggler.vue';
import ThemToggler from './ThemToggler.vue';
import {useAuthStore} from '~/stores/auth';
import { useLocaleStore } from '~/stores/locale';

const localeStore = useLocaleStore();
const authStore = useAuthStore();

const toggleSidebar = ref(false);

const navItems = [
  {
    english_name:'Home',
    bangla_name: 'হোম',
    hindi_name: 'होम',
    link:'/',
  },

  {
    english_name:'Provider',
    bangla_name: 'প্রোভাইডার',
    hindi_name: 'प्रोवाइडर',
    link:'/category/providers/jili'
  },
  
  {
    english_name:'Category',
    bangla_name: 'ক্যাটাগরি',
    hindi_name: 'कैटेगरी',
    link:'/category/slot'
  },
]

const responsiveNavItems = [
  {
    english_name:'Login',
    bangla_name: 'লগইন',
    hindi_name: 'रजिस्टर',
    link:'/login',
  },

  {
    english_name: 'Sign Up',
    bangla_name: 'সাইন আপ',
    hindi_name: 'साइन अप',
    link: '/register',
  }

]

const name = computed(() => localeStore.getTranslate('name'))
const userBalance = computed(() => authStore.user?.balance ?? 0)

onMounted(() =>{
  if(authStore.token){
    authStore.recallUser();
  }
});

</script>

