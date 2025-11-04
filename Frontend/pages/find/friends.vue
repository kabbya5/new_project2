<template>
    <div class="px-4 py-4 container mx-auto">
        <div v-if="friend_requests.length > 0">
            <h2 class="text-gray-800 font-bold text-xl"> Friend Request </h2>
            <div class="grid grid-cols-5 gap-4 mt-4">
                <div class="border-2 border-gray-400 shadow-xl" v-for="friend in friend_requests" :key="friend.id">
                    <img :src="friend.profile_picture" :alt="friend.name">
                    <NuxtLink :to="`/profile/${friend.id}`">{{ friend.name }}</NuxtLink>
                    <button @click="confirmFriend(friend.id)" class="text-center py-2 w-full text-white bg-blue-500"> Confirm </button>
                    <button @click="deleteRequest(friend.id)" class="text-center py-2 w-full text-white bg-red-500"> Delete </button>
                </div>
            </div>
        </div>
       

        <div class="mt-4" v-if="find_friends.length > 1">
            <h2 class="text-gray-800 font-bold text-xl"> Add Friend </h2>
            <div class="grid grid-cols-5 gap-4 mt-4">
                <div v-for="friend in find_friends" :key="friend.id" class="mb-4 border-2 border-gray-400 shadow-xl">
                    <img :src="friend.profile_picture" :alt="friend.name ">
                    <NuxtLink :to="`/profile/${friend.id}`">{{ friend.name }}</NuxtLink>
                    <button @click="sendFriendRequest(friend.id)" class="text-center py-2 w-full text-white bg-cyan-500"> Add Friend  </button>
                    <button class="text-center py-2 w-full text-white bg-red-500"> Delete </button>
                </div>
            </div>
        </div>
    </div>

</template>

<script setup lang="ts">
import { useAuthStore } from '~/stores/authStore';

definePageMeta({
    middleware: 'auth',
});
const friend_requests = ref<any[]>([]);

const find_friends = ref<any[]>([]);
const hasMore = ref<boolean>(true)
const { $echo } = useNuxtApp();
const query = ref({
    page: 1,
    limit: 30,
});

const {getUser}  = useAuthStore();
const userId = computed(() => getUser()?.id || '');

const getFriendRequests = async () => {
    const response = await useCustomFetch(`/friends/requests`);
    if(response.value){
        friend_requests.value = response.value.friend_request;
        console.log(friend_requests.value);
    }
}

const findFriends = async () => {
    const { page, limit } = query.value;

    if (hasMore.value) {
        try {
            const res = await useCustomFetch(`/friends/find?page=${page}&limit=${limit}`);
            const data = res.value;
            if (data) {
                const friends = data.fiend_friends || [];
                if (friends.length < limit) {
                    hasMore.value = false;
                }
                find_friends.value.push(...friends);

                query.value.page++;
            }
        } catch (error) {
            console.error('Error fetching friends:', error);
        }
    }

};

const confirmFriend = async (friend_id: number)  =>{
    friend_requests.value = friend_requests.value.filter(f => f.id != friend_id);
    try{
        await useCustomFetch('/friends/confirm/' + friend_id, {
            method: 'POST'
        }); 
    }catch(error){
        console.error("Error sending friend request:", error);
    }
    
}

const deleteRequest = async (friend_id: number) => {
    try {
        friend_requests.value = friend_requests.value.filter(f => f.id !== friend_id);

        const response = await useCustomFetch(`/friends/requests/${friend_id}`, {
            method: "DELETE",
        });

        if (!response.ok) {
            throw new Error("Failed to delete friend request");
        }

        console.log("Friend request deleted successfully");
    } catch (error) {
        console.error("Error deleting friend request:", error);
    }
};

const handelScroll = () => {
    if (window.innerHeight + window.scrollY > document.body.scrollHeight) {
        findFriends();
    }
}

const sendFriendRequest = async (friend_id: number) => {
    find_friends.value = find_friends.value.filter(f => f.id !== friend_id);
    try {
        await useCustomFetch('/friends/requests/' + friend_id, {
            method: 'POST'
        });

    } catch (error) {
        console.error("Error sending friend request:", error);
    }
};

onMounted(async () => {
    getFriendRequests();
    findFriends();
    window.addEventListener('scroll', handelScroll); 

    if (userId.value) {
        $echo.private(`friend_request-${userId.value}`)
            .listen('FriendRequestEvent', (e:any) => {
                friend_requests.value.push(e.sender);
            });
    } else {
        console.log("User ID is not available.");
    }
});

onBeforeMount(() => {
    getFriendRequests();
    findFriends();
    window.removeEventListener('scroll', handelScroll);
});

</script>