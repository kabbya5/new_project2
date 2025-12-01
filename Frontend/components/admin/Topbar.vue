<template>
    <div id="admin-topbar" class="bg h-[54px] text-white flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
        <div class="flex items-center">
            <AdminButtonsSidebarToggler/>
            <div class="flex pl-4">
                <i class="pt-1 fa-solid fa-search"> </i>
                <input type="text" placeholder="Search here"
                    class="px-3 border-b border-gray-200 dark:border-gray-800 focus:outline-none">
            </div>
        </div>
        
        <div class="flex items-center space-x-3">
            <!-- <AdminLangToggler /> -->
            <!-- <FrontendNavNotificationToggler  /> -->
            <!-- <FrontendNavThemToggler class="pr-2" /> -->
        </div>

    </div>
</template>

