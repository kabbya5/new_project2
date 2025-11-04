<template>
    <div class="flex justify-center items-center h-full w-full py-4">
      <div class="w-full max-w-[600px] shadow-xl bg-white p-6 rounded-lg border">
        <div class="flex justify-center items-center mb-6">
          <h2 class="text-2xl font-medium text-dark">Sign Up</h2>
        </div>
        <form @submit.prevent="handleSignup">
          <div class="mb-4">
            <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
            <input 
              type="text" 
              id="username" 
              v-model="signupForm.name" 
              required 
              class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
            />
          </div>
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input 
              type="email" 
              id="email" 
              v-model="signupForm.email" 
              required 
              class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
            />
          </div>
          <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input 
              type="password" 
              id="password" 
              v-model="signupForm.password" 
              required 
              class="mt-1 block w-full p-2 border border-gray-300 rounded-md"
            />
          </div>
          <div class="flex justify-center">
            <button 
              type="submit" 
              class="w-full py-2 px-4 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200"
            >
              {{ buttonText }}
            </button>
          </div>
        </form>
        <div v-if="errorMessage" class="text-red-600 text-center mt-2">{{ errorMessage }}</div>
        <div class="text-center mt-4">
          <p>Already have an account? <NuxtLink to="/login" class="text-blue-600 hover:underline">Login here</NuxtLink></p>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  
  import type { Signup } from '~/types/signup';
  
  const buttonText = ref('Signup');
  const errorMessage = ref<string | null>(null); // Error message for user feedback
  
  const signupForm = ref<Signup>({
    name: '',
    email: '',
    password: '',
  });
  const router = useRouter()
  const config = useRuntimeConfig();
  const baseURL = config.public.baseURL;
  
  const handleSignup = async () => {
    errorMessage.value = null; // Reset error message
    try {
      const response = await useCustomFetch(`${baseURL}/api/signup`, {
        method: 'POST',
        body: signupForm.value as Signup,
      });

      if (response && response.status === 200) { // Assuming success status code is 200
        router.push('/')
      }

    } catch (err) {

      errorMessage.value = err.message || 'An unexpected error occurred.';
      console.error('Signup error:', err);
    }
  };
  </script>
  
  <style scoped>
  /* Add any additional styles here */
  </style>