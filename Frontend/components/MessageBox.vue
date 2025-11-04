<template>
  <div class="fixed bottom-4 right-3 flex space-x-4"
     v-if="Object.keys(messageStore.togglerMessageBox).length > 0">
   
   <div class="w-96 bg-gray-100 p-3 rounded-lg shadow-lg" 
        v-for="[id, messageBox] in Object.entries(messageStore.togglerMessageBox)" 
        :key="id">
        
     <div class="flex justify-between items-center bg-green-500 text-white p-2 rounded-t-lg">
       <div class="flex items-center">
          <img :src="messageBox.profile_picture" alt="" class="w-8 h-8 rounded-full mr-2">
          <span> {{messageBox.name}} </span>
       </div>
      
       <button class="text-xl" @click="toggleMessageBox(messageBox.id)">&times;</button>
     </div>
 
     <div class="p-2 space-y-2 h-80 overflow-y-auto pb-10" 
        :ref="(el) => setMessageBoxRef(el, messageBox.id)" @scroll="handelScroll(messageBox.id)">
        <div v-for="msg in getMessages(messageBox.id)" :key="msg.id">
          <div v-if="msg.sender_id == userId" class="w-full flex justify-end bg-white text-gray-800 p-2 rounded-lg shadow">
            <div v-if="msg.message_type === 'audio'">
              <audio :src="msg.content" controls preload="metadata" />
            </div>
            
            <p v-else>{{ msg.content }}</p>
          </div>
          <div v-else class="bg-green-100 text-black text-semibold p-2 rounded-lg shadow">
              <p class="">{{ msg.content }}</p>
          </div>
        </div>
     </div>

    <div v-if="previews[messageBox.id]?.length > 0" class="flex flex-wrap items-center">
      <div
        v-for="(preview, index) in previews[messageBox.id]"
        :key="index"
        class="mr-1 relative border-2 border-gray-300 p-2 rounded-md"
      >
        <button @click="removeImage(messageBox.id,index)" class="absolute z-50 top-0 right-0 bg-gray-300 px-2 py-1 text-red-500 text-[10px]"> X </button>
        
        <img v-if="preview.type=='img'" :src="preview.url" alt="image" class="h-12 w-12">

        <div v-else-if="preview.type=='video'" class="h-20 w-20">
          <video :src="preview.url" controls class="h-full w-full rounded object-cover" />
        </div>

        <div v-else-if="preview.type === 'pdf'">
            <iframe :src="preview.url" class="h-full w-full rounded"></iframe>
        </div>

        <template v-else>
          <p class="text-xs text-red-500">Unsupported</p>
        </template>
      </div>
      
      <label for="file" class="ml-2 bg-black text-white flex items-center justify-center w-[30px] h-[30px] rounded-full">
        <font-awesome class="text-sm" :icon="['fas','plus']"> </font-awesome>
      </label>
    </div>
 
     <div class="mt-2 flex items-center border-t pt-2">
        <div v-if="isAudioInput" class="w-full flex items-center bg-blue-500 text-white rounded-xl px-2 py-1 w-fit">
          <button @click="cancleRecord(messageBox.id)" class="text-white hover:text-red-200 mr-4">
            <font-awesome class="text-xl" :icon="['fas','xmark']" /> 
          </button>

          <button @click="recordingComplete ? togglePlayPause(messageBox.id) : stopRecording()" class="bg-white text-blue-500 rounded-full px-2 py-1 mr-4 hover:bg-gray-200">
            <font-awesome class="text-sm" :class="recordingComplete ? (isPlaying ? 'text-blue-600' : 'text-red-500') : 'text-red-500'" 
                          :icon="['fas', isPlaying ? 'pause' : (recordingComplete ? 'play' : 'stop')]"/>
          </button>

          <!-- Timer -->
          <div class="bg-white text-blue-600 rounded-full px-2 py-1 text-sm font-medium mr-2">
            {{ formattedTime }}
          </div>
        </div>
        <div v-else class="w-full">
          <button @click="startRecorrding(messageBox.id)" class="border-none pr-2"> 
            <font-awesome class="text-xl" :icon="['fas','microphone']" />
          </button>

          <label for="file" class="pr-2">
            <font-awesome class="text-xl" :icon="['fas','file-image']" /> 
          </label>

          <input type="file" @change="handelFIleChange(messageBox.id,$event)" name="file" class="hidden" id="file" multiple>

          <input v-model="newMessage[messageBox.id].message" type="text" 
            placeholder="Type a message..."
            class="flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
        </div>
       
        <button @click="sendMessage(messageBox.id)" 
          class="ml-2 bg-green-500 text-white px-3 py-1 rounded-lg shadow">Send</button>
      </div>
   </div>
 </div>
</template>

<script setup lang="ts">

interface MessageData {
  message?: string | null;
  audio?: Blob | null;
  files?: File[] | null;
}

interface PreviewFile{
  name:string,
  type:string,
  url:string,
}

import { useMessagesStore } from '@/stores/messageToggler';
import { useAuthStore } from '@/stores/authStore';
import { ref, nextTick, computed, onMounted, watch } from 'vue';

const { getUser } = useAuthStore();
const messageStore = useMessagesStore();

const messageBoxRefs = ref<{ [key: number]: HTMLElement | null }>({});
const newMessage = ref<{ [key: number]: MessageData }>({});
const isAudioInput = ref<boolean>(false);
const timer = ref(0)
const isPlaying = ref(false);
const recordingComplete = ref<boolean>(false);

let mediaRecorder: MediaRecorder | null = null;
let audioChunks: Blob[] = [];
let audioPlayer = ref<HTMLAudioElement | null>(null);
const userId = computed(() => getUser()?.id || '');

const previews = ref<{ [key: number]: PreviewFile[] }>({})

const toggleMessageBox = (receiverId: number) => {
  messageStore.hideShowMessageBox(receiverId);
};

let interval: number | NodeJS.Timeout;

const startRecorrding = async(receiverId: number) => {
  
  isAudioInput.value = true;
  recordingComplete.value = false;
   
  const stream = await navigator.mediaDevices.getUserMedia({audio:true})

  mediaRecorder = new MediaRecorder(stream)
  audioChunks = []
  timer.value = 0

  mediaRecorder.ondataavailable = (event:BlobEvent) =>{
    audioChunks.push(event.data)
  }

  mediaRecorder.onstop = () => {
    if(!newMessage.value[receiverId]){
      newMessage.value[receiverId] = {
        message: null,
        audio: new Blob([]),
        files: [],
      }
    }
    newMessage.value[receiverId].audio = new Blob(audioChunks, { type: 'audio/webm' })
    recordingComplete.value = true;
    audioChunks = []
    clearInterval(interval)
    stream.getTracks().forEach(track => track.stop());
  }

  mediaRecorder.start()

  interval = setInterval(() => {
    timer.value++
  }, 1000)
}

const formattedTime = computed(() => {
  const mins = Math.floor(timer.value / 60)
  const secs = timer.value % 60
  return `${mins}:${secs < 10 ? '0' : ''}${secs}`
})

const togglePlayPause = (reciverId:number) => {
  if (isPlaying.value) {
    audioPlayer.value?.pause();
  } else {
    if (newMessage.value[reciverId].audio) {
      const audioBlob = newMessage.value[reciverId].audio;
      const audioUrl = URL.createObjectURL(audioBlob);
      audioPlayer.value = new Audio(audioUrl);
      audioPlayer.value.play();
    }
  }
  isPlaying.value = !isPlaying.value; 
};

const cancleRecord = (receiverId:number) =>{
    isAudioInput.value = false;
} 

const stopRecording = () => {
  if (mediaRecorder && mediaRecorder.state !== 'inactive') {
    mediaRecorder.stop();
    stream.getTracks().forEach(track => track.stop());
  }
};

const handelFIleChange = (receiverId:number,event:Event) =>{
  const input = event.target as HTMLInputElement;
  const files = Array.from(input.files || []);

  if(!newMessage.value[receiverId]){
    newMessage.value[receiverId] = {
      message:'',
      files:[],
      audio:new Blob([]),
    };
  }

  if (!previews.value[receiverId]) {
    previews.value[receiverId] = [];
  }

  newMessage.value[receiverId].files = [
    ...(newMessage.value[receiverId].files || []),
    ...files
  ];

  files.forEach(file => {
    let url = URL.createObjectURL(file)
    let type = file.type 

    if(type.startsWith('image/')){
      type = 'img';
    }else if(type.startsWith('video/')){
      type = 'video';
    }else if(type ==='application/pdf'){
      type = 'pdf'
    }

    previews.value[receiverId].push({
      name:file.name,
      type:type,
      url:url,
    })
  })
}

const removeImage = (receiverId:number,index:number) =>{
  if(previews.value[receiverId]){
    previews.value[receiverId].splice(index,1)
  }

  if (previews.value[receiverId]?.[index]) {
    previews.value[receiverId].splice(index, 1);
  }
}


const sendMessage = (receiverId: number) => {
  if (!newMessage.value[receiverId]) return;

  messageStore.sendMessage(receiverId, newMessage.value[receiverId]);

  newMessage.value[receiverId] = {
    message: null,
    audio: new Blob([]),
    files: [],
  };
  isAudioInput.value = false;
  scrollToBottom(receiverId);
};

const getMessages = (receiverId: number): any[] => {
  const userMessages = messageStore.messages.find(m => m.receiverId === receiverId);
  return userMessages?.messages ?? [];
};

const setMessageBoxRef = (el: HTMLElement | null, id: number) => {
  if (el) {
    messageBoxRefs.value[id] = el;
  }
};

const scrollToBottom = (receiverId: number) => {
  nextTick(() => {
    const messageBox = messageBoxRefs.value[receiverId];
    if (messageBox) {
      messageBox.scrollTop = messageBox.scrollHeight;
    }
  });
};

const handelScroll = (receiverId:number) =>{
  const messageBox = messageBoxRefs.value[receiverId];
  if(messageBox){
    const {scrollTop, scrollHeight, clientHeight} = messageBox;
    const isNearBottom = scrollHeight - (scrollTop + clientHeight) <= 100;
    if(isNearBottom){
        messageStore.readMessage(receiverId);
    }
  }
}

onMounted(() => {
  // Scroll to bottom for all message boxes on mount
  Object.keys(messageStore.togglerMessageBox).forEach(id => {
    scrollToBottom(Number(id));
  });
});

watch(
  () => messageStore.messages,
  () => {
    Object.keys(messageStore.togglerMessageBox).forEach(id => {
      if(!newMessage.value[id]){
        newMessage.value[id] =  newMessage.value[id] = { message: null, audio: null, files:[]};
      }
      scrollToBottom(Number(id));
    });
  },
  { deep: true }
);

watch(
  () => messageStore.togglerMessageBox,
  () => {
    // Scroll to bottom when a new message box is opened
    Object.keys(messageStore.togglerMessageBox).forEach(id => {
      scrollToBottom(Number(id));
    });
  },
  { deep: true }
);
</script>

<style scoped>
/* Add your styles here */
</style>