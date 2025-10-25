<template>
  <div class="container mx-auto pb-6 pt-1">
    
    <div class="bg-white dark:bg-gray-800 shadow-lg">
        <h2 class="px-2 py-2"> Update User </h2>
    </div>

    <div class="grid grid-cols-12 gap-4 mt-4">
        <div class="col-span-12 lg:row-span-2 lg:col-span-7 p-3 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
            <h2 class=""> Basice Information </h2>

            <form @submit.prevent="submit" class="mt-3">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">  Name  </label>
                        <input v-model="userForm.name" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required
                          :class="errorStore.validationErrors.name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.name" class="text-red-500">  {{ errorStore.validationErrors.name[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> User Name </label>
                        <input v-model="userForm.user_name" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required readonly
                          :class="errorStore.validationErrors.user_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.user_name" class="text-red-500">  {{ errorStore.validationErrors.user_name[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Phone Number </label>
                        <input v-model="userForm.phone_number" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> Email </label>
                        <input v-model="userForm.email" type="email" class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" required 
                        :class="errorStore.validationErrors.email ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.email" class="text-red-500">  {{ errorStore.validationErrors.email[0] }} </p>
                    </div>

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> Submit </button>
                </div>   
            </form>
        </div>

        <div class="col-span-12 lg:col-span-5 p-3 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
            <h2 class=""> Password and Security </h2>

            <form @submit.prevent="submit" class="mt-3">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">  Password </label>
                        <input v-model="userForm.password" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required
                          :class="errorStore.validationErrors.password ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.password" class="text-red-500">  {{ errorStore.validationErrors.password[0] }} </p>
                    </div>

                    <button type="submit" class="w-full py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> Update Password </button>
                </div>   
            </form>
        </div>

        <div class="col-span-12 lg:col-span-5 p-3 bg-white dark:bg-gray-800 shadow-lg rounded-2xl">
            <h2 class=""> Manage User </h2>

            <form @submit.prevent="submit" class="mt-3">
                <div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">  Name  </label>
                        <select v-model="userForm.role" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required
                          :class="errorStore.validationErrors.name ? 'border-red-500' :'' ">

                          <option value="user"> User </option>
                          <option value="agent"> Agent </option>
                          <option value="affiliate"> Affiliate </option>
                          <option value="affiliate"> Admin </option>

                        </select>
                        
                        <p v-if="errorStore.validationErrors.name" class="text-red-500">  {{ errorStore.validationErrors.name[0] }} </p>
                    </div>

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> Submit </button>
                </div>   
            </form>
        </div>
    </div>
  </div>
</template>

<script setup lang="ts">

definePageMeta({
  middleware: ['auth', 'admin'],
  layout:'admin',
})

import type { UserForm } from '~/types/userForm';

import { useLocaleStore } from '~/stores/locale';
import { useAuthStore } from '~/stores/auth';
import { useErrorStore } from '~/stores/error';

const localeStore = useLocaleStore();
const errorStore = useErrorStore();
const authStore = useAuthStore();

const userForm = ref<UserForm>({
  name: '',
  user_name: '',
  phone_number: '',
  email: '',
  password:'',
  role:'',
})

const router = useRouter();
const route = useRoute();
const userId = route.params.id;

onMounted( async () =>{
    const response :any = await useApiFetch('/admin/find/user/' + userId);

    if(response.data){
      const data = response.data;
      userForm.value.name = data.name;
      userForm.value.user_name = data.user_name;
      userForm.value.phone_number = data.phone;
      userForm.value.email = data.email;
      userForm.value.role = data.role;
    }

});


const submit = async () => {
  try {
    const formData = new FormData();
    formData.append('name', userForm.value.name || '')
    formData.append('user_name', userForm.value.user_name || '')
    formData.append('email', userForm.value.email || '')
    formData.append('phone', userForm.value.phone_number || '')
    formData.append('role', userForm.value.role || '')
    if(userForm.value.password){
      formData.append('password', userForm.value.password);
    }

    const response:any = await useApiFetch('/admin/update/user/' + userId, {
      method: 'POST',
      body: formData,
    });

    if(response){
      await router.push('/admin/user');
    }
  } catch (error:any) {
    console.log(error.message || error);
  }
};

</script>