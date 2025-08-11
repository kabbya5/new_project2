<template>
    <div class="relative">
        <button @click="togglerNotification" class="cursor-pointer">
            <i class="text-gray-900  dark:text-gray-100 text-[20px] fa-solid fa-bell"> </i>
        </button>

        <div v-if="showNotifications" class="notification-box absolute top-[220%] -right-[60%] w-[400px]">
            <div class="header px-3 py-2 flex justify-between items-center bg-red-500 dark:bg-black">
                <h2 class="text-white"> Notification </h2>
                <button @click="closeNotification" class="flex items-center justify-center rounded-full w-[30px] h-[30px]
                    border-1 border-slate-100/20">
                    <i class="text-gray-100 fa-solid fa-xmark"></i>
                </button>
                
            </div>

            <div class="body bg-white dark:bg-black px-1 py-2 border-2 border-gray-200 dark:border-gray-900">
                <div class="flex items-center">
                    <i class="text-[40px] text-gray-900 dark:text-gray-100 fa-solid fa-clock"> </i>
                    <div>
                        <h2 class="text-xl text-gray-900 dark:text-gray-100 px-3 font-[500]"> Notification Title  </h2>
                        <p class="text-gray-900 dark:text-gray-100 px-3 text-left"> Details  </p>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    
</template>

<script setup lang="ts">
    const showNotifications = ref<boolean>(false)

    const togglerNotification = () =>{
        showNotifications.value = !showNotifications.value;
    }

    const closeNotification = () => {
        showNotifications.value = false;
    }
</script>