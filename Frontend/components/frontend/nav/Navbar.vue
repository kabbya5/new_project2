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

        <div>
          <div class="hidden lg:flex">
            <ul class="nav-items flex items-center space-x-4">

              <li class="nav-link" v-for="item in navItems" :key="item.name">
                <NuxtLink :to="item.link" class="nav-item text-gray-800 dark:text-gray-100">{{ item.name }}</NuxtLink>
              </li>
            
              <li class="nav-link"><NotificationToggler /></li>
              <li class="nav-link"><ThemToggler /></li>
              <LangToggler />
            </ul>
          </div>

          <div class="flex items-center lg:hidden">
            <button id="menu-toggle" class="text-gray-900 dark:text-gray-100 hover:text-indigo-500 focus:outline-none">
              ☰
            </button>
          </div>

        </div>
      </div>
    </nav>
  </div>
</template>

<script setup lang="ts">
import NotificationToggler from './NotificationToggler.vue';
import ThemToggler from './ThemToggler.vue';


const navItems = [
  {
    name:'Home',
    link:'/',
  },

  {
    name:'Register',
    link:'/register',
  },

  {
    name:'Login',
    link:'/login',
  },

  {
    name:'dashboard',
    link:'/admin/dashboard'
  },
  {
    name:'Service',
    link:'/service',
  }
]
</script>

