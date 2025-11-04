<template>
  <MessageList v-if="messageStore.showOldMessage" class="fixed top-14 right-2 border-2 border-gray-200 z-50"/>
    <div class="shadow-md dark:bg-black bg-white border-b border-2 broder-gray-400 dark:border-gray-700">
      <div class="container mx-auto px-2 py-3">
        <div class="flex justify-between items-center">

          <div class="w-1/3 flex items-center">
            <NuxtLink to="/" class="text-dark dark:text-white">
              <FontAwesomeIcon class="text-blue-800 dark:text-white" :icon="['fab','facebook']" size="2xl" />
            </NuxtLink>
            <div class="form-group ralative mx-3">
              <input type="text" class="absolut p-2 border border-2 
                border-slate-300 rounded-md h-9 w-48 focus:outline-none" 
                placeholder="Search here..." >
            </div>
          </div>

          <div class="w-1/3 flex items-center justify-between">
            <NuxtLink to="/" class="text-dark dark:text-white">
              <FontAwesomeIcon class="text-gray-600 dark:text-white" :icon="['fas','home']" size="2xl" />
            </NuxtLink>

            <NuxtLink to="/" class="text-dark dark:text-white">
              <FontAwesomeIcon class="text-gray-600 dark:text-white" :icon="['fas','play']" size="2xl" />
            </NuxtLink>

            <NuxtLink :to="{name:'find-friends'}" class="text-dark dark:text-white" active-class="active-link">
              <FontAwesomeIcon class="text-gray-600 dark:text-white fa" :icon="['fas','people-group']" size="2xl" />
            </NuxtLink>
          </div>

          <div class="w-1/3 flex items-center justify-end">
            <div class="flex items-center justify-between w-full lg:w-1/2">
              <button class="text-dark relative dark:text-white" @click="toggleMessageList">
                <FontAwesomeIcon class="text-gray-600 dark:text-white fa" :icon="['fab','facebook-messenger']" size="2xl"/>
                <span class="absolute bg-red-500 text-white top-[-10px] right-[-10px] w-[20px] h-[20px] rounded-full flex items-center justify-center"> {{ unReadMessage  }} </span>
              </button>

              <NuxtLink to="/" class="text-dark dark:text-white">
                <FontAwesomeIcon class="text-gray-600 dark:text-white" :icon="['fas','bell']" size="2xl" />
              </NuxtLink>
            
              <button 
                  @click="darkModeStore.toggleDarkMode" 
                  class="dark-mode-toggle text-black dark:text-white"
                >
                <FontAwesomeIcon :icon="darkModeStore.isDark ? 'sun' : 'moon'" size="2xl" />
              </button>
            </div>
          </div>

          
          <!-- <div class="flex w-full justify-center">
              <button 
                v-if="userName" 
                @click="handelLogout"  
                class="text-2xl font-md text-dark dark:text-white"
              >
                {{ userName }} Log out 
              </button>
            
              <NuxtLink 
                v-else 
                :to="{ name: 'login' }" 
                class="text-2xl font-md text-dark dark:text-white"
              >
                Login
              </NuxtLink>
            </div>
          </div> -->
          
          
        </div>
      </div>

      <div v-for="notification in notificationStore.notifications" :key="notification.id">
            <NotificationMessage
                :message="notification.message"
                :type="notification.type"
                @dismiss="notificationStore.removeNotification(notification.id)"
            />
      </div>
    </div>
</template>
  
<script setup lang="ts">
  import { useDarkModeStore } from '@/stores/darkMode'; 
  import { useAuthStore } from '~/stores/authStore';
  import { useNotificationStore } from '#imports';
  import { useOnlineFriendsStore } from '@/stores/onlineFriends';
  import { useMessagesStore } from '@/stores/messageToggler';

  const notificationStore = useNotificationStore();
  const messageStore = useMessagesStore();
  const apiUrl = getBaseUrl();
  const authStore = useAuthStore();
  const darkModeStore = useDarkModeStore();
  const {getUser}  = useAuthStore();
  const onlineFriendsStore = useOnlineFriendsStore();

  const userName = computed(() => getUser()?.name || '');
  const unReadMessage = computed(() => messageStore.unreadMessages);

  const clearAuthData = () => {
    authStore.clearAuthData(); 
  };

  const toggleMessageList = () =>{
    // messageStore.getOldMessage();
    messageStore.toggleMessageList()
  };

  const handelLogout = async() =>{
    try{
      const response = await useCustomFetch(`${apiUrl}/logout`,{
        method:'POST',
      });

      if (response && response._rawValue.success) {
          notificationStore.addNotification('Successfully Low out', 'info');
          clearAuthData();
      }else{
        
        notificationStore.addNotification('Logout Faild', 'error');
      }
    } catch(error){
      clearAuthData();
      notificationStore.addNotification('Logout Faild', 'error');
    }
  }
</script>

<style>
.active-link .fa{
  color: rgb(218, 122, 13);
}
</style>
  