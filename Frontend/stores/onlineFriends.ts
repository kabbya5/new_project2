import {defineStore} from 'pinia';

interface Friend{
    id:number,
    name:string,
    isOnline:Date,
    profile_picture:string|null,

}

export const useOnlineFriendsStore = defineStore('onlineFriends', {
    state:() =>({
        onlineFriends:[] as Friend[],
    }),

    actions:{ 
        async getOnlineFriends(){
            try{
                const baseUrl = await getBaseUrl(); 
                const response = await useCustomFetch(`/friends/online`);
                const data = response.value?.friends;
                if (Array.isArray(data)) {

                    this.onlineFriends = data;
                }
            }catch(error){
                console.error('Error fetching online friends:', error);
            }
        }      
    }
});