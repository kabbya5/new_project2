<template>
    <div class="py-4 flex items-center justify-center w-full">
        <div class="w-full max-w-[500px] bg px-4 py-2 shadow-xl">
            <div class="border-b border-gray-300 pb-2">
                <h2 class="text-green-600 font-semibold text-xl tracking-[1.4px]"> {{ updatePassword }} </h2>
            </div>  
       
            <div class="my-4">
                <form @submit.prevent="submit">
                  <div>
                    <div class="mb-4 relative">
                      <label class="block text-sm font-medium  mb-1">
                        {{ oldPassword }}
                      </label>
                      <input
                        v-model="signupForm.old_password"
                        :type="showOldPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        required
                        :class="errorStore.validationErrors.password ? 'border-red-500' : '' "
                      />

                      <!-- Toggle button -->
                      <button
                        type="button"
                        class="absolute right-3 top-9 text-gray-500"
                        @click="showOldPassword = !showOldPassword"
                      >
                        <span v-if="showOldPassword">🙈</span>
                        <span v-else>👁️</span>
                      </button>

                      <p v-if="errorStore.validationErrors.old_password" class="text-red-500">
                        {{ errorStore.validationErrors.old_password[0] }}
                      </p>
                    </div>

                    <div class="mb-4 relative">
                      <label class="block text-sm font-medium  mb-1">
                        {{ password }}
                      </label>
                      <input
                        v-model="signupForm.password"
                        :type="showPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        required
                        :class="errorStore.validationErrors.password ? 'border-red-500' : '' "
                      />

                      <!-- Toggle button -->
                      <button
                        type="button"
                        class="absolute right-3 top-9 text-gray-500"
                        @click="showPassword = !showPassword"
                      >
                        <span v-if="showPassword">🙈</span>
                        <span v-else>👁️</span>
                      </button>

                      <p v-if="errorStore.validationErrors.password" class="text-red-500">
                        {{ errorStore.validationErrors.password[0] }}
                      </p>
                    </div>

                    <div class="mb-4 relative">
                      <label class="block text-sm font-medium  mb-1">
                        {{ confirmPassword }}
                      </label>
                      <input
                        v-model="signupForm.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        required
                        :class="errorStore.validationErrors.password_confirmation ? 'border-red-500' : '' "
                      />

                      <!-- Toggle button -->
                      <button
                        type="button"
                        class="absolute right-3 top-9 text-gray-500"
                        @click="showConfirmPassword = !showConfirmPassword"
                      >
                        <span v-if="showConfirmPassword">🙈</span>
                        <span v-else>👁️</span>
                      </button>

                      <p v-if="errorStore.validationErrors.password_confirmation" class="text-red-500">
                        {{ errorStore.validationErrors.password_confirmation[0] }}
                      </p>
                    </div>

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> Update </button>
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

definePageMeta({
    middleware: 'auth'
});

const router = useRouter();
const showPassword = ref(false)
const showConfirmPassword = ref(false)
const showOldPassword = ref(false)

const signupForm = ref<any>({
    old_passsword:'',
    password: '',
    password_confirmation:'',
});

const errorStore = useErrorStore();
const authStore = useAuthStore();

const updatePassword = computed(() => localeStore.getTranslate('updatePassword'));
const oldPassword = computed(() => localeStore.getTranslate('oldPassword'));
const password = computed(() => localeStore.getTranslate('password'))
const confirmPassword = computed(() => localeStore.getTranslate('confirmPassword'))

const submit = async () => {
  try {
    const formData = new FormData();
    formData.append('old_password', String(signupForm.value.old_password ?? ''));
    formData.append('password', String(signupForm.value.password ?? ''));
    formData.append('password_confirmation', String(signupForm.value.password_confirmation ?? ''));

    const response = await useApiFetch('/update/password', {
      method: 'POST',
      body: formData,
    });

    const token = response.token;
    const user = response.user;

    if (token && user) {
      authStore.setToken(token);
      authStore.setUser(user);
      alert('Password updated successfully');
      await router.push('/profile');
    }
    
    

  } catch (error) {
    console.log(error.message || error);
  }
};

</script>