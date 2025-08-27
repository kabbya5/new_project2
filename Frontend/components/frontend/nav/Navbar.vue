<template>
  <div id="navbar" class="bg-white dark:bg-black border border-gray-300 dark:border-gray-600 shadow-md dark:shadow-lg">
    <nav class="py-2 rounded container mx-auto">
      <div class="flex justify-between items-center">
        <div class="flex-shrink-0 flex items-center">
          <!-- Light mode logo -->
          <NuxtImg src="logo/logo-white.png" alt="Logo" class="block dark:hidden h-14 w-auto" />

          <!-- Dark mode logo -->
          <NuxtImg src="logo/logo-dark.png" alt="Logo" class="hidden dark:block h-14 w-auto" />
        </div>

        <div class="flex items-center">
          <div class="hidden lg:flex">
            <ul class="nav-items flex items-center space-x-4">

              <li class="nav-link" v-for="item in navItems" :key="item.name">
                <NuxtLink :to="item.link" class="nav-item text-gray-800 dark:text-gray-100">{{ item[name] }}</NuxtLink>
              </li>
            
              <li class="nav-link"><NotificationToggler /></li>
              <li class="nav-link"><ThemToggler /></li>
              <LangToggler />
            </ul>
          </div>

          <div class="px-3">
            <button @click="toggleSidebar = !toggleSidebar" class="text-gray-900 dark:text-gray-100 hover:text-indigo-500 focus:outline-none">
              <i class="fa-solid fa-bars text-[25px]"></i>
            </button>
          </div>

        </div>
      </div>
    </nav>
  </div>

  <!-- sidebar  -->

  <FrontendNavSidebar v-if="toggleSidebar"  @close="toggleSidebar = false" />

</template>

<script setup lang="ts">
import NotificationToggler from './NotificationToggler.vue';
import ThemToggler from './ThemToggler.vue';

import { useLocaleStore } from '~/stores/locale';

const localeStore = useLocaleStore();

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
    name: 'प्रोवाइडर',
    link:'/category/providers/jili'
  },
  
  {
    english_name:'Category',
    bangla_name: 'ক্যাটাগরি',
    hindi_name: 'कैटेगरी',
    link:'/category/slot'
  },

  {
    english_name:'Register',
    bangla_name: 'রেজিস্টার',
    link:'/register',
  },

  {
    english_name:'Login',
    bangla_name: 'লগইন',
    hindi_name: 'रजिस्टर',
    link:'/login',
  },

  {
    english_name:'dashboard',
    hindi_name: 'dashboard',
    bangla_name: 'dashboard',
    link:'/admin/dashboard'
  },
]

const name = computed(() => localeStore.getTranslate('name'))

</script>

