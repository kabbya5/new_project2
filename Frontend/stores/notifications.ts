import { defineStore } from 'pinia';
interface Notification{
    id:number,
    message:string,
    type:string,
}

export const useNotificationStore = defineStore('notification',{
     state: () => ({
        notifications: [] as Notification[],
    }),

    actions:{
        addNotification(message:string, type: string ='info'){
            const id = Date.now();
            this.notifications.push({id,message,type});

            setTimeout(() =>{
                this.removeNotification(id);
            }, 3000);
        },

        removeNotification(id:number){
           this.notifications =  this.notifications.filter(notification => notification.id !== id);
        }
    }
});