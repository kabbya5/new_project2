<template>
    <div id="admin-topbar" class="bg-white dark:bg-black h-[54px] flex justify-between items-center border-b border-gray-300 dark:border-gray-700">
        <div class="flex items-center">
            <AdminButtonsSidebarToggler/>
        </div>
        
        <div class="flex items-center space-x-3 py-4">
            <AdminLangToggler />
            <!-- <FrontendNavNotificationToggler  /> -->
            <FrontendNavThemToggler class="pr-2" />
        </div>

    </div>
</template>

