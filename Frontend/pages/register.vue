<template>
    <div class="py-4 flex items-center justify-center w-full">
        <div class="w-full max-w-[500px] bg-white dark:bg-gray-800 px-4 py-2 shadow-xl"> 
            <div class="header flex justify-between items-center">
                <h2 class="text-xl font-semibold"> Register </h2>
                <NuxtLink to="/login" class="text-cyan-500 "> Login </NuxtLink>
            </div>
            
            <div class="mt-4">
                <form @submit.prevent="submit">
                    <ul v-if="Object.keys(errorStore.validationErrors).length">
                        <li v-for="(messages, field) in errorStore.validationErrors" :key="field" class="text-red-500">
                            {{ field }}: {{ messages.join(', ') }}
                        </li>
                    </ul>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                        <input v-model="signupForm.name" type="text" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input v-model="signupForm.email" type="email" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input v-model="signupForm.password" type="password" class="w-full px-4 py-2 border rounded-lg dark:bg-gray-700 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    </div>

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">Register</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useAuthStore } from '~/stores/auth';
import { useErrorStore } from '~/stores/error';
import type {SignUp} from '~/types/signup';

const router = useRouter();

const signupForm = ref<SignUp>({
    name: '',
    email: '',
    password: '',
});

const errorStore = useErrorStore();
const {setToken, setUser, getUser} = useAuthStore();

const submit = async () => {
  try {
    const response = await useApiFetch('/signup', {
      method: 'POST',
      body: signupForm.value,
    });

    if (response.error) {
      console.log(response.error);
    } else {
      const data = response.data;
      if (data && data.token) {
        setToken(data.token);
        setUser(data.user);
      }

      await router.push('/');
    }
  } catch (error) {
    console.log(error.message || error);
  }
};

</script>