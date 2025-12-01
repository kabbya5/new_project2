<template>
    <div class="py-4 flex items-center justify-center w-full">
        <div class="w-full max-w-[500px] bg px-4 py-2 shadow-xl">
            <div class="border-b border-gray-300 pb-2">
                <h2 class="text-green-600 font-semibold text-xl tracking-[1.4px]"> {{updateProfile }} </h2>
            </div>  
       
            <div class="my-4">
                <form @submit.prevent="submit">
                  <div>
                    <div class="form-group mb-4">
                        <label for="" class="text-lg"> {{ image }} </label>
                        <div class="flex">
                            <input type="file" class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" 
                                placeholder="En Name"
                                @change="handelImageUpload" 
                                :class="errorStore.validationErrors.image ? 'border-red-500' :'' ">

                            <img v-if="imagePreview" :src="imagePreview" alt="" class="ml-2 mt-2 w-[40px] h-[40px]">
                        </div>
                        
                        <p v-if="errorStore.validationErrors.image" class="text-red-500">  {{ errorStore.validationErrors.image[0] }} </p>
                        
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">  {{ nameInput }}  </label>
                        <input v-model="signupForm.name" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required
                          :class="errorStore.validationErrors.name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.name" class="text-red-500">  {{ errorStore.validationErrors.name[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1"> {{ userName }} </label>
                        <input v-model="signupForm.user_name" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required readonly
                          :class="errorStore.validationErrors.user_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.user_name" class="text-red-500">  {{ errorStore.validationErrors.user_name[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1"> {{ currency }} </label>
                        <input type="text" v-model="signupForm.currency" required readonly
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1"> {{ phoneNumber }} </label>
                        <input v-model="signupForm.phone_number" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1"> {{ email }} ({{ optional }})</label>
                        <input v-model="signupForm.email" type="email" class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" required 
                        :class="errorStore.validationErrors.email ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.email" class="text-red-500">  {{ errorStore.validationErrors.email[0] }} </p>
                    </div>

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> {{ updateProfile }} </button>
                  </div>   
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';

import { useAuthStore } from '~/stores/auth';
import { useErrorStore } from '~/stores/error';

const localeStore = useLocaleStore();
const errorStore = useErrorStore();
const authStore = useAuthStore();

definePageMeta({
    middleware: 'auth'
});

const router = useRouter();


const signupForm = computed(() => ({
  name: authStore.user?.name || '',
  user_name: authStore.user?.user_name || '',
  phone_number: authStore.user?.phone || '',
  email: authStore.user?.email || '',
  currency: authStore.user?.currency,
  image:null,
}));

const image = computed(() => localeStore.getTranslate('image'));
const updateProfile = computed(() => localeStore.getTranslate('updateProfile'));
const nameInput = computed(() => localeStore.getTranslate('nameInput'))
const currency = computed(() => localeStore.getTranslate('currency'))
const userName = computed(() => localeStore.getTranslate('userName'))
const phoneNumber = computed(() => localeStore.getTranslate('phoneNumber'))
const optional = computed(() => localeStore.getTranslate('optional'))
const email = computed(() => localeStore.getTranslate('email'))

const imagePreview = ref<string | null>(null);

const handelImageUpload = (event:Event) =>{
    const file = (event.target as HTMLInputElement).files?.[0]
    if(file){
        signupForm.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

const submit = async () => {
  try {
    const formData = new FormData();
    formData.append('name', signupForm.value.name);
    formData.append('user_name', signupForm.value.user_name);
    formData.append('email', signupForm.value.email);
    formData.append('phone_number', signupForm.value.phone_number);
    if(signupForm.value.image){
      formData.append('image', signupForm.value.image);
    }
    
    const response:any = await useApiFetch('/update/profile', {
      method: 'POST',
      body: formData,
    });

    if(response.user){
      authStore.setUser(response.user);
      await router.push('/profile');
    }
  } catch (error) {
    console.log(error.message || error);
  }
};

</script>