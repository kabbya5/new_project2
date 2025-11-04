import { defineStore } from "pinia";

type MessageBox = {
    id: number;
    name: string;
    profile_picture: string;
};

interface MessageData {
    message: string | null;
    audio: Blob | null;
    file: File[] | null;
}

export const useMessagesStore = defineStore('messages', {
    
    state: () => ({
        showOldMessage:false,
        oldMessageList:[] as any,
        unreadMessages:  1,
        messages: [] as Array<{ receiverId: number; messages: Array<any> }>,
        togglerMessageBox: {} as Record<number, MessageBox>,
    }),

    actions: { 
        async getMessage(receiverId: number) {
            try {
                const response = await useCustomFetch('/messages/' + receiverId);
                const data = response.value;
                if (data.messages) {
                    let existingMessage = this.messages.find(m => m.receiverId === receiverId);

                    if (!existingMessage) {
                        this.messages.push({ receiverId, messages: [...data.messages] });
                    } else {
                        existingMessage.messages.push(...data.messages);
                    }
                 
                    this.togglerMessageBox[receiverId] = data.receiver;
                }
            } catch (error) {
                console.log(error);
            }  
        },

        pushMessage(receiverId: number, data: any) {
            const existingIndex = this.messages.findIndex(m => m.receiverId === receiverId);

            if (existingIndex !== -1) {
                this.messages[existingIndex].messages.push(data);
            } else {
                this.messages.push({ receiverId: receiverId, messages: [data] });
            }
        },

        async sendMessage(receiverId: number, data: MessageData) {
            try {
              const formData = new FormData();
          
              if (data.message) formData.append('message', data.message);
              if (data.audio && data.audio.size > 0) {
                formData.append('audio', data.audio, 'recording.webm');
              }
              if (data.file && data.file.size > 0) {
                formData.append('file', data.file);
              }
          
              const response = await useCustomFetch('/messages/' + receiverId, {
                method: 'POST',
                body: formData,
              });
          
              if (response.value && response.value.message) {
                this.pushMessage(receiverId, response.value.message);
              }
            } catch (error) {
              console.error('Send message error:', error);
            }
        },

        hideShowMessageBox(receiverId: number) {
            delete this.togglerMessageBox[receiverId];
        },

        async getOldMessage(){
            if (!this.oldMessageList.length) {
                try {
                    const response = await useCustomFetch('/messages/old/list');
                    const data = response.value;
                    if (data.messages) {
                        this.oldMessageList = data.messages;
                        this.unreadMessages = 0;
    
                        data.messages.forEach((element:any) => {
                            if (element.is_read === 0) {
                                this.unreadMessages += 1;
                            }
                        });
                    }
                } catch (error) {
                    console.log(error);
                } 
            } 
        },

        async readMessage(receiverId:number){
            try{
                await useCustomFetch(`/messages/${receiverId}/mark-as-read`,{
                    method:'PUT',
                });
                const data = response.value;

                if (data.status === 'success') {
                    this.oldMessageList.map(message =>{
                        if(message.id === receiverId){
                            message.is_read = true
                        }
                    })
                    this.unreadMessages -= 1;
                }else{
                    console.error('Failed to mark messages as read:', data.message);
                }
            } catch (error) {
                console.error('Error marking messages as read:', error);
            }
        },

        toggleMessageList(){
            this.showOldMessage = !this.showOldMessage;
        },
    }
});
