<template>
<div class="w-96 bg-white shadow-lg rounded-lg p-4">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-semibold">Chats</h2>
        <button @click="toggleMessageList" class="border-2 border-gray-200 shadow-md px-3 py-1 rounded-full"> X </button>
    </div>

    <div class="mb-4">
        <input type="text" placeholder="Search Messenger"
            class="w-full p-2 border rounded-lg focus:outline-none focus:ring focus:border-blue-300">
    </div>

    <div class="flex mb-2">
        <button class="flex-1 p-2 text-center text-blue-600">Inbox</button>
    </div>

    <div class="space-y-4 overflow-y-auto h-screen">
        <div v-for="message in messageStore.oldMessageList" :key="message.id"
             class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-2 rounded-lg"
             @click="showMessageBox(message.id)">
            <img :src="message.receiver_image" alt="User" class="w-12 h-12 rounded-full">
            <div class="flex-1">
                <h3 class="font-semibold">{{ message.receiver_name }} {{ message.id }}</h3>
                <p class="text-sm text-gray-500 truncate" :class="message.is_read ? 'text-gray-500' : 'text-gray-600 font-bold'">{{ strLimit(message.message,35) }}</p>
            </div>
        </div>
    </div>
</div>
</template>

<script setup lang="ts">

import { useMessagesStore } from '@/stores/messageToggler';
const messageStore = useMessagesStore();

const toggleMessageList = () =>{
    messageStore.toggleMessageList();
};

const showMessageBox = (receiverId:any) => {
  messageStore.getMessage(receiverId);
  messageStore.toggleMessageList();
};

onMounted(async () =>{
    await messageStore.getOldMessage();
})

</script>