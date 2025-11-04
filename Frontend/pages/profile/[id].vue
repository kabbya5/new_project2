<template>
    <div class="bg-slate-200/60">
       <div class="profile-section">
            <div class="with-[100%] h-[400px] overflow-hidden bg-white border-2 border-bottom border-gray-300">
                <img class="w-full h-full object-cover" src="https://plus.unsplash.com/premium_photo-1701534008693-0eee0632d47a?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8d2Vic2l0ZSUyMGJhY2tncm91bmR8ZW58MHx8MHx8fDA%3D" alt="">
            </div>

            <div class="container mx-auto px-2 py-4">
                <div class="flex justify-between items-end">
                    <div class="flex items-center">
                        <img id="fakeImage" src="https://picsum.photos/200/200" class="w-24 h-24 rounded-full">
                        <div class="flex flex-col ml-6">
                            <p class="text-2xl font-xl text-black"> {{ friend.name }} </p>
                            <p class="text-md font-md text-gray-600"> 1.6k friends * 41 mutual </p>
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                                <img class="w-8 h-8 rounded-full -ml-[5px]" src="https://shorturl.at/TFk0q" alt="">
                            </div>
                        </div>
                    </div>

                    <div class="flex">
                        <button v-if="friendshipStatus == 'pending'" @click="calcleFriendRequest"
                            class="flex items-center justify-center bg-orange-500 text-white px-4 py-2 rounded-md"> 
                            <font-awesome class="text-xl text-white mr-2" :icon="['fas','user']" /> 
                            Celcle Request
                        </button>
                        <button v-else-if="friendShipStatus == 'accepted'" 
                            class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded-md"> 
                            <font-awesome class="text-xl text-white mr-2" :icon="['fas','user-plus']" /> 
                            Friend  
                        </button>
                        <button v-else @click="sendFriendRequest"
                            class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded-md"> 
                            <font-awesome class="text-xl text-white mr-2" :icon="['fas','user']" /> 
                            Add Friend 
                        </button>
                        <button class="flex items-center justify-center bg-blue-500 text-white px-4 py-2 rounded-md mx-4"> <font-awesome class="text-xl text-white mr-2" :icon="['fab','facebook-messenger']" /> Message{{ friendshipStatus.value }} </button>
                    </div>
                </div>

                <div class="mt-10">
                    
                </div>
            </div>
       </div>
    </div>
</template>
  
<script setup>
import { useAuthStore } from '~/stores/authStore';
const friend = ref('');
const friendshipStatus = ref('');
const route = useRoute();

const authStore = useAuthStore();
const {getUser}  = useAuthStore();
const userId = computed( () => getUser()?.id || '');

const getFriendStatus = async () => {
  try {
    const friendId = route.params.id;
    const data  = await useCustomFetch(`/friends/status/${friendId}`);

    if (data.value) {
        friend.value = data.value.friend; 
        friendshipStatus.value = data.value.status;
    }else{
        friendshipStatus.value = 'not_friends';
    }
  }catch (error) {
    console.error("Error fetching friend status:", error);
    friendshipStatus.value = 'error';
  }
};

const sendFriendRequest = async () => {
    try {
        const friend_id = route.params.id;
        const response =  await useCustomFetch('/friends/requests/' + friend_id, {
            method: 'POST'
        });
        if (response.value.status) {
            
            friendshipStatus.value = response.value.status;
        }
    } catch (error) {
        console.error("Error sending friend request:", error);
    }
};

const calcleFriendRequest = async () => {
    try{
        const friend_id = route.params.id;
        const response = await useCustomFetch('/friends/request/cancle/' + friend_id,{
            method: 'DELETE'
        });

        if(response.value){
           
            friendshipStatus.value = false;
        }

        console.log(friendshipStatus.value);
    }catch(error){
        alert('server error');
    }
}

onMounted(() =>{
    getFriendStatus();
});
onBeforeMount(() => {
    getFriendStatus();
});

</script>