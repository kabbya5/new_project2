<template>
<div class="fixed top-0 left-0 w-full h-screen bg-gradient-to-br from-sky-400 via-indigo-500 to-purple-600 z-50 flex text-white">
    <div class="sidebar pt-6 px-4 bg-white h-screen w-64 relative">
        <div class="flex items-center pb-3 border-2 border-bottom border-gray-200">
            <button @click="closeForm" class="close-day-form bg-gray-500  mr-4 text-white w-12 h-12 rounded-full">  X  </button>
            <NuxtLink to="/" class=" text-black"> 
                <font-awesome :icon="['fab', 'facebook']" class="text-5xl" />
            </NuxtLink>
        </div>

        <div class="absolute bottom-0 left-0 w-full bg-red-500 py-4">
            <div class="flex items-center justify-center">
                <button @click="clearPreview"> Cancle  </button>
                <button @click="submitDay" class="ml-4"> Save  </button>
            </div>
        </div>
    </div>

    <div class="text-center px-6 w-full relative">
        <div class="w-full">
            <h1 class="text-6xl font-bold mb-4 animate-bounce">Beautiful Day</h1>
            <p class="text-lg font-medium">Make the most out of your day!</p>
            <div class="flex justify-center items-center h-screen w-full">
                
                <!-- Photo Story Section -->
                <label for="day-image" class="bg-white w-64 h-96 flex items-center justify-center flex-col cursor-pointer">
                    <font-awesome-icon class="text-4xl text-red-500" :icon="['fas','photo-film']" /> 
                    <p class="text-gray-600 font-semibold mt-2 text-xl"> Create a Photo story </p>
                </label>
                <input @change="handleFileChange" type="file" id="day-image" class="hidden" accept="image/*">

                <!-- Text Story Section -->
                <div class="ml-8 bg-white w-64 h-96 flex items-center justify-center flex-col cursor-pointer">
                    <font-awesome-icon class="text-4xl text-red-500" :icon="['fas','photo-film']" /> 
                    <p class="text-gray-600 font-semibold mt-2 text-xl"> Create a Text story </p>
                </div>
            </div>
        </div>

        <!-- Image Preview Section -->
        <div v-if="day.preview" id="imagePreviewContainer" class="image-preview bg-white absolute top-0 left-0 w-full border-2 border-l border-gray-300">
            <h2 class="text-center text-lg font-semibold p-4">Image Preview</h2>
            <div class="flex justify-center items-center p-4">
                <img id="imagePreview" :src="day.preview" alt="Image Preview" class="w-64 h-64 object-cover rounded-lg shadow-lg">
            </div>
        </div>
    </div>
</div>
</template>

<script setup lang="ts">

const props = defineProps({
  closeForm: {
    type: Function,
    required: true,
  },
});

const day = ref<{
  image: File | null;
  preview: string | null;
}>({
  image: null,
  preview: null,
});

const handleFileChange = (event:Event) => {
    const input = event.target as HTMLInputElement;
    if(input.files && input.files[0]){
        const file = input.files[0]
        day.value.image = file
        day.value.preview = URL.createObjectURL(file);
    }
} 

const clearPreview = () => {
    if (day.value.preview) {
        URL.revokeObjectURL(day.value.preview ); 
        day.value.preview  = null; 
        day.value.image = null;  
    }
};

</script>