<template>
    <div v-if="sidebar.isOpen" class="flex bg-white dark:bg-black" id="admin-sidebar">
        <!-- Sidebar -->
        <aside>
            <!-- Logo  -->
            <NuxtLink to="/admin/dashboard" class="flex justify-center items-center border-b border-gray-300 dark:border-gray-700 py-3">
                <img src="https://stackbros.in/darkone/assets/images/logo-dark.png" class="w-6 h-6" alt="Logo" />
                <span class="font-bold text-lg">Darkone</span>
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
                        <i
                          :icon="open[link.name] ? ['fas', 'chevron-up'] : ['fas', 'chevron-down']"
                        />
                      </NuxtLink>
                    </div>

                    <!-- <div v-if="open[link.name]" class="ml-2 space-y-1 flex flex-col">
                      i18n texts for auth links 
                      <NuxtLink
                        to="/auth/signin"
                        class="sidebar-sublink my-2 py-1 rounded-md px-4 bg-gray-200/60"
                        >{{ ('auth.signin') }}</NuxtLink
                      >
                      <NuxtLink to="/auth/signup" class="sidebar-sublink"
                        >{{ ('auth.signup') }}</NuxtLink
                      >
                      <NuxtLink to="/auth/reset-password" class="sidebar-sublink"
                        >{{ ('auth.reset_password') }}</NuxtLink
                      >
                      <NuxtLink to="/auth/lock-screen" class="sidebar-sublink"
                        >{{ 'auth.lock_screen' }}</NuxtLink
                      >
                    </div> -->
                  </div>
                </div>


                <div class="text-xs uppercase text-gray-400 dark:text-gray-500 pt-4">UI Kit...</div>

                <!-- <div class="sidebar-section" v-for="section in uiSections" :key="section.name">
                    <div class="sidebar-dropdown flex justify-between mb-4" @click="toggleDropdown(section.name)">
                        <span class="flex items-center space-x-2">
                            <i :class="section.icon"></i>
                            <span>{{ section.title }}</span>
                        </span>
                         <i :class="['fas', open[section.name] ? 'fa-chevron-up' : 'fa-chevron-down']"></i>
                    </div>
                    <div v-if="open[section.name]" class="ml-6 space-y-1">
                        <NuxtLink v-for="link in section.links" :key="link.title" :to="link.route" class="sidebar-sublink">
                            {{ link.title }}
                        </NuxtLink>
                    </div>
                </div> -->
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
  ])
  
const uiSections = computed(() => [
  {
    name: 'baseui',
    title: 'nav.sections.baseui.title',
    icon: 'fas fa-leaf',
    links: [
      { title: 'sections.baseui.links.buttons', route: '/ui/buttons' },
      { title: 'sections.baseui.links.cards', route: '/ui/cards' }
    ]
  },
  {
    name: 'charts',
    title: 'sections.charts.title',
    icon: 'fas fa-chart-bar',
    links: [
      { title: 'nav.sections.charts.links.line', route: '/charts/line' }
    ]
  },
  {
    name: 'forms',
    title: 'sections.forms.title',
    icon: 'fas fa-lock',
    links: [
      { title: 'sections.forms.links.elements',route: '/forms/elements' }
    ]
  },
  {
    name: 'tables',
    title: 'sections.tables.title',
    icon: 'fas fa-table',
    links: [
      { title: 'sections.tables.links.basic', route: '/tables/basic' }
    ]
  },
  {
    name: 'icons',
    title: 'sections.icons.title',
    icon: 'fas fa-globe',
    links: [
      { title: 'sections.icons.links.fa', route: '/icons/fontawesome' }
    ]
  }
])
  
  const toggleSidebar = () => {
    showSidebar.value = !showSidebar.value
  }
  
  const toggleDropdown = (section) => {
    open.value[section] = !open.value[section]
  }
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
  

  