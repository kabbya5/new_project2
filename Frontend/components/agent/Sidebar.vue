<template>
    <div v-if="sidebar.isOpen" class="h-full bg-white dark:bg-black" id="admin-sidebar">
        <!-- Sidebar -->
        <aside>
            <!-- Logo  -->
            <NuxtLink to="/agent/dashboard" class="flex justify-center items-center border-b border-gray-300 dark:border-gray-700 py-3">
                <span class="text-xl capitalize"> {{ authStore?.getUser()?.name ?? 'Loading' }} </span>
                <span class="text-xl ml-2">({{ userBalance ?? 'Loading' }}) </span>

            </NuxtLink>
    
            <nav class="space-y-1 px-3 py-2">
                <NuxtLink to="/agent/dashboard" class="sidebar-link">
                    <div class="flex justify-between items-center">
                        <span class="flex items-center space-x-2">
                            <i class="fas fa-home"></i>
                            <span>Dashboard</span>
                        </span>
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
const userBalance = computed(() => authStore.user?.balance ?? 0)

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
    if(authStore.token){
      authStore.recallUser();
    }
  });

  onUnmounted(() =>{
    window.removeEventListener('resize', updateWindowSize);
  });

  const navLinks = computed(() => [
    {
      name:'Deposit',
      title:'Deposit',
      icon:'fa-solid fa-comments-dollar',
      url:'/agent/transaction/deposit',
    },

    {
      name:'Withdraw',
      title:'withdraw',
      icon:'fa-solid fa-coins',
      url:'/agent/transaction/withdraw',
    },
    {
      name:'transaction',
      title:'Transaction',
      icon:'fa-solid fa-wallet',
      url:'/agent/transaction',
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
  

  