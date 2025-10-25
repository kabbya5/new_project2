<template>
    <div v-if="sidebar.isOpen" class="flex bg-white dark:bg-black" id="admin-sidebar">
        <!-- Sidebar -->
        <aside>
            <!-- Logo  -->
            <NuxtLink to="/admin/dashboard" class="flex justify-center items-center border-b border-gray-300 dark:border-gray-700 py-3">
                <NuxtImg src="/logo.png" class="h-7" />
            </NuxtLink>
    
            <!-- Menu -->
            <div class="text-xs px-3 py-2 uppercase text-gray-400 dark:text-gray-500">
                Menu...
            </div>

            <nav class="space-y-1 px-3 py-2">
                <NuxtLink to="/" class="sidebar-link">
                    <div class="flex justify-between items-center">
                        <span class="flex items-center space-x-2">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </span>
                        <span class="text-xs bg-purple-600 text-white rounded-full px-2 py-0.5">03</span>
                    </div>
                </NuxtLink>

                <div class="sidebar-section mt-2">
                  <div v-for="link in navLinks" :key="link.name">
                    <div
                      class="menu flex justify-between items-center"
                      @click="toggleDropdown(link.name)"
                    >
                      <NuxtLink :to="link.url" class="menu-item flex items-center w-full justify-between" active-class="text-blue-600 font-bold">
                        <span class="space-x-3 py-1">
                          <i :icon="link.icon" :class="link.icon" />
                          <span>{{ link.name }}</span>
                        </span>
                      </NuxtLink>
                    </div>
                  </div>
                </div>

            </nav>
        </aside>
    </div>
</template>
  
<script setup lang="ts">
  import { useSidebarTogglerStore } from '~/stores/sidebarToggler';
  import SidebarToggler from './buttons/SidebarToggler.vue';
  const sidebar = useSidebarTogglerStore();

  const showSidebar = ref(false)
  const open = ref({
    auth: false,
    baseui: false,
    forms: false,
    charts: false,
    tables: false,
    icons: false
  })

  const navLinks = computed(() => [
    {
      name:'slider',
      title:'Slider',
      icon:'fa-regular fa-images',
      url:'/admin/slider',
    },

    {
      name:'text',
      title:'Moving Text',
      icon:'fa-solid fa-text-width',
      url:'/admin/text',
    },

    {
      name:'category',
      title:'Category',
      icon:'fa-regular fa-calendar-days',
      url:'/admin/category',
    },

    {
      name:'provider',
      title:'Provider',
      icon:'fa-regular fa-bookmark',
      url:'/admin/provider',
    },

    {
      name:'game',
      title:'Game',
      icon:'fa-solid fa-trophy',
      url:'/admin/game',
    },
    {
      name:'transaction',
      title:'Transaction',
      icon:'fa-solid fa-wallet',
      url:'/admin/transaction',
    },
    {
      name:'Game Records',
      title:'Game Records',
      icon:'fa-solid fa-receipt',
      url:'/admin/transaction/record',
    },
     {
      name: 'currency',
      title: 'Currency',
      icon: 'fa-solid fa-coins',
      url: '/admin/currency'
    },

    {
      name: 'Users',
      title: 'Users',
      icon: 'fa-solid fa-user-secret',
      url: '/admin/user'
    },

    {
      name: 'Label',
      title: 'label',
      icon: 'fa-solid fa-tag',
      url: '/admin/label'
    },
  ])
  
</script>

<style scoped>

.nuxt-link-active span{
  color: blue !important;
}

#admin-sidebar {
  width: 230px !important;
}

#admin-sidebar aside {
  width: 100%;
}

#admin-sidebar aside .sidebar-section .menu-item {
  display: flex;
  align-items: center;
  padding: 4px 0px;
  border-radius: 6px;
  cursor: pointer;
  transition: all 0.2s ease;
  text-transform: capitalize;
}

#admin-sidebar aside .sidebar-section .menu-item .menu-item-icon {
  font-size: 14px;
}

#admin-sidebar aside .sidebar-section .menu-item:hover {
  color: #007bff;
}

#admin-sidebar aside .sidebar-section .menu-item.active {
  color: #007bff;
  font-weight: bold;
}
</style>
  

  