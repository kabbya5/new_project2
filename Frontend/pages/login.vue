<template>
    <div class="flex justify-center items-center h-full w-full py-10">
        <div class="w-full max-w-[400px] shadow-xl rounded-lg bg-white">
            <div class="flex justify-center items-center bg-black dark:bg-slate-200/60 py-3">
                <h2 class="text-2xl font-medium tracking-[1px] text-white dark:text-gray-700">Login</h2>
            </div>
            <form @submit.prevent="handleLogin">
                <div style="padding:8px 15px;">
                    <div class="mb-4">
                        <label for="identifier" class="block text-sm font-medium text-gray-700"> Phone / User Name </label>
                        <input 
                            type="text" 
                            id="email" 
                            v-model="loginForm.identifier" 
                            required 
                            class="mt-1 block w-full p-2 border border-gray-300 text-gray-700 rounded-md"
                        />
                    </div>
                    <div class="mb-4 relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <input 
                        :type="showPassword ? 'text' : 'password'" 
                        id="password" 
                        v-model="loginForm.password" 
                        required 
                        class="mt-1 block w-full p-2 pr-10 border border-gray-300 rounded-md text-gray-700"
                        />
                        <!-- Eye toggle button -->
                        <button 
                            type="button" 
                            @click="showPassword = !showPassword" 
                            class="absolute inset-y-0 top-5 right-2 flex items-center text-gray-400"
                        >
                            <span v-if="showPassword">👁️</span>
                            <span v-else>🙈</span>
                        </button>

                        <!-- Validation error -->
                       <p v-if="errorStore.validationErrors.password" class="text-red-500">  {{ errorStore.validationErrors.password[0] }} </p>
                    </div>
                    <div class="flex justify-center mb-4">
                        <button 
                            type="submit" 
                            class="w-full py-2 px-4 bg-blue-600 text-white font-medium rounded-md hover:bg-blue-700 transition duration-200"
                        >
                            Login
                        </button>
                    </div>
                    <div class="left flex mb-4">
                        <p class="text-gray-700">Already have an account?</p>
                        <NuxtLink to="/register" class="text-blue-600 hover:underline ml-2">Sign up here</NuxtLink>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">

definePageMeta({
    middleware: 'auth'
});

import { useAuthStore } from '~/stores/auth';
import { useErrorStore } from '~/stores/error';

const errors = ref('');
const {setToken, setUser, getUser} = useAuthStore();
const errorStore = useErrorStore();
const router = useRouter();
const showPassword = ref(false);

interface loginType{
    identifier:string,
    password:string,
}

const loginForm = ref<loginType>({
    identifier:'',
    password: '',
});



const handleLogin = async() =>{
    errors.value = '';
    try{
        const data = await useApiFetch('/login', {
            method: 'POST',
            body: loginForm.value,
        });


        if (data && data.token) {
            setToken(data.token);
            setUser(data.user);
            if(data.user.role == 'admin'){
                await router.push('/admin/dashboard');
            }else if(data.user.role == 'agent'){
                await router.push('/agent/dashboard');
            }else if(data.user.role == 'affiliate'){
                await router.push('/affiliate/dashboard');
            }else{
                await router.push('/');
            }
        } else {
            console.log(data.error);
        }
    }catch(error){
        console.log(error);
    }
}

</script>

<style scoped>
/* Add any additional styles here */
</style>