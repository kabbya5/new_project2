<template>
    <div v-if="sidebar.isOpen" class="h-full bg-white dark:bg-black" id="admin-sidebar">
        <!-- Sidebar -->
        <aside>
            <!-- Logo  -->
            <NuxtLink to="/admin/dashboard" class="flex justify-center items-center border-b border-gray-300 dark:border-gray-700 py-3">
                <NuxtImg src="/logo.png" class="h-5 md:h-7" />
            </NuxtLink>
    
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
                    <div class="menu">
                      <NuxtLink :to="link.url" class="menu-item flex items-center w-full justify-between" active-class="text-blue-600 font-bold">
                        <span class="space-x-3 py-1">
                          <i :icon="link.icon" :class="link.icon" />
                          <span>{{ link.name }}</span>
                        </span>
                      </NuxtLink>
                    </div>
                  </div>

                  <div>
                    <div class="menu">
                      <button @click="logout" class="menu-item flex items-center w-full justify-between" active-class="text-blue-600 font-bold">
                        <span class="space-x-3 py-1">
                          <i class="fa-solid fa-right-from-bracket"></i>
                          <span> Logout</span>
                        </span>
                      </button>
                    </div>
                  </div>
                </div>
            </nav>
        </aside>
    </div>
</template>
  
<script setup lang="ts">
  import {useAuthStore} from '~/stores/auth';
  const sidebar = useSidebarTogglerStore();

  const authStore = useAuthStore();
  const {handleLogout} = authStore;

  const updateWindowSize = () => {
    if (window.innerWidth <= 1024) {
      sidebar.close();
    } else {
      sidebar.open();
    }
  }

  onMounted(() =>{
    updateWindowSize();
    window.addEventListener('resize', updateWindowSize);
  });

  onUnmounted(() =>{
    window.removeEventListener('resize', updateWindowSize);
  });

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
      name:'Transaction Number',
      title:'Transaction Number',
      icon:'fa-solid fa-ad',
      url:'/admin/transaction/deposit-number',
    },

    {
      name:'Promotion & Affiliate',
      title:'Promotion & Affiliate',
      icon:'fa-solid fa-text-width',
      url:'/admin/promotion',
    },

    {
      name:'Deposit',
      title:'Deposit',
      icon:'fa-solid fa-comments-dollar',
      url:'/admin/transaction/deposit',
    },

    {
      name:'Withdraw',
      title:'withdraw',
      icon:'fa-solid fa-coins',
      url:'/admin/transaction/withdraw',
    },

    {
      name:'Movine Text',
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
      name: 'VIP Level',
      title: 'VIP Level',
      icon: 'fa-solid fa-tag',
      url: '/admin/label'
    },

    {
      name: 'Agent',
      title: 'Agent',
      icon: 'fa-solid fa-user-group',
      url: '/admin/agents',
    },

    {
      name: 'Affiliate',
      title: 'affiliate',
      icon: 'fa-solid fa-users-line',
      url: '/admin/affiliate'
    },
  ])
  
const router = useRouter();

const logout = async () => {
  await handleLogout();    
  router.push('/');  
}

</script>

<style scoped>

.nuxt-link-active span{
  color: blue !important;
}

#admin-sidebar {
  min-width: 230px !important;
  max-width: 230px;
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
  

  