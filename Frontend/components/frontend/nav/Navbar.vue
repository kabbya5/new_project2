<template>
  <div id="navbar" class="fixed top-0 w-full z-100 side-menu shadow-md dark:shadow-lg">
    <nav class="py-2 rounded container mx-auto">
      <div class="hidden md:flex justify-between items-center">
        <div class="flex-shrink-0 flex items-center px-2">
         
          <button @click="toggleSidebar = !toggleSidebar" class="hover hover-text focus:outline-none">
            <i class="fa-solid fa-bars text-[25px]"></i>
          </button>
            
          <NuxtLink to="/" class="ml-10 -mt-1">
             <NuxtImg class="w-[210px]" src="logo.png" />
          </NuxtLink>
        </div>

        
        <div>
          <ul class="nav-items flex items-center space-x-4">
            <li class="nav-link" v-for="(item,index) in navItems" :key="index">
              <NuxtLink :to="item.link" class="nav-item">{{ item[name] }}</NuxtLink>
            </li>

     
            <li v-if="!authStore.token" class="nav-link" v-for="(item,index) in responsiveNavItems" :key="index">
              <NuxtLink :to="item.link" class="nav-item">{{ item[name] }}</NuxtLink>
            </li>

            <li v-else class="nav-link" v-if="authStore.getUser()?.name">
              <p class=""> 
                <i class="fa-solid fa-user"></i>
                <span class="uppper ml-2 uppercase"> {{authStore.getUser()?.currency }} {{authStore.user?.balance }}</span>
              </p>
            </li>

            <li class="nav-link">
              <FrontendMessenger />
            </li>

            <li class="nav-link"><LangToggler /></li>
            
          </ul>
        </div>
      </div>

      <div class="px-3 flex justify-between md:hidden items-center">
          <ul class="nav-items flex items-center justify-between space-x-4 w-full">
            <li> 
              <button @click="toggleSidebar = !toggleSidebar" class="hover hover-text focus:outline-none">
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
                  <p class=""> 
                    <i class="fa-solid fa-user"></i>
                    <span class="uppper ml-2 uppercase"> {{authStore.getUser()?.currency }} {{userBalance}}</span>
                  </p>
                </li>

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

