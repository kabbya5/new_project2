<template>
    <div class="py-4 flex items-center justify-center w-full">
        <div class="w-full max-w-[500px] bg-white dark:bg-gray-800 px-4 py-2 shadow-xl">
            <div class="bg-white dark:bg-gray-800 border-b border-gray-300 pb-2">
                <h2 class="text-green-600 font-semibold text-xl tracking-[1.4px]"> Hello </h2>
                <p class="text-sm lg:text-md my-1"> Welcome to luckbuzz99.com</p>
            </div>  
       
            <div class="my-4">
                <form @submit.prevent="submit">
                  <div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> {{ userName }} </label>
                        <input v-model="signupForm.user_name" type="text" 
                          class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                          required
                          :class="errorStore.validationErrors.user_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.user_name" class="text-red-500">  {{ errorStore.validationErrors.user_name[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> {{ currency }} </label>

                        <FormSelectWithImage class="w-full" :options="currencyOptions" :selected="selectedCurrency"  @update:selected="selectedCurrency = $event" 
                        :class="errorStore.validationErrors.currency ? 'border-red-500' :'' " />
                        
                        <p v-if="errorStore.validationErrors.currency" class="text-red-500">  {{ errorStore.validationErrors.currency[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> {{ phoneNumber }} </label>
                        <div class="flex">
                           <FormSelectWithImage class="w-[30%]" :options="codeOptions" :selected="selectedCode"  @update:selected="selectedCode = $event"
                           :class="errorStore.validationErrors.phone_number ? 'border-red-500' :'' " />

                          <input v-model="signupForm.phone_number" type="text" class="w-full ml-2 px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" placeholder="- - - - - - - - -" required
                          :class="errorStore.validationErrors.phone_number ? 'border-red-500' :'' " />
                        </div>
                                              
                        <p v-if="errorStore.validationErrors.phone_number" class="text-red-500">  {{ errorStore.validationErrors.phone_number[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"> {{ email }} ({{ optional }})</label>
                        <input v-model="signupForm.email" type="email" class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" required 
                        :class="errorStore.validationErrors.email ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.email" class="text-red-500">  {{ errorStore.validationErrors.email[0] }} </p>
                    </div>

                    <div class="mb-4">
                        <label @click="showReferInput = !showReferInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">  {{ referCode  }} ({{ optional }})
                          <i v-if="!showReferInput" class="fa-solid fa-angle-up px-1"></i>
                          <i v-else class="fa-solid fa-angle-down px-1"></i>
                        </label>
                        <input v-if="showReferInput" v-model="signupForm.refer_code" type="text" class="w-full px-4 py-2 border rounded-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200" required 
                        :class="errorStore.validationErrors.refer_code ? 'border-red-500' :'' ">
                        <p v-if="errorStore.validationErrors.refer_code" class="text-red-500">  {{ errorStore.validationErrors.refer_code[0] }} </p>
                    </div>

                    <div class="mb-4 relative">
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
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
                      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
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

                    <button type="submit" class="w-full py-2 mt-4 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition"> Register </button>
                  </div>   
                </form>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';
import type {SignUp} from '~/types/signup';

import { useAuthStore } from '~/stores/auth';
import { useErrorStore } from '~/stores/error';

const localeStore = useLocaleStore();

definePageMeta({
    middleware: 'auth'
});

const router = useRouter();

const showReferInput = ref<boolean>(false)
const showPasswordForm = ref<boolean>(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

const signupForm = ref<SignUp>({
    user_name:'',
    phone_number:'',
    email: '',
    password: '',
    refer_code:'',
    password_confirmation:'',
});

const currencyOptions = [
  { value: "inr", label: "INR", image: "/Flag/in.png" },
  { value: "bdt", label: "BDT", image: "/Flag/bd.png" },
  { value: "npr", label: "NPR", image: "/Flag/np.png" },
  { value: "pkr", label: "PKR", image: "/Flag/pk.png" },
  { value: "lkr", label: "LKR", image: "/Flag/lk.png" },
  { value: "btn", label: "BTN", image: "/Flag/bt.png" },
  { value: "aed", label: "AED", image: "/Flag/ae.png" }
]

const selectedCurrency = ref(currencyOptions[1])

const phoneCodeMap: Record<string, { value: string; label: string; image: string }[]> = {
  inr: [{ value: "+91", label: "+91", image: "/Flag/in.png" }],
  bdt: [{ value: "+880", label: "+880", image: "/Flag/bd.png" }],
  npr: [{ value: "+977", label: "+977", image: "/Flag/np.png" }],
  pkr: [{ value: "+92", label: "+92", image: "/Flag/pk.png" }],
  lkr: [{ value: "+94", label: "+94", image: "/Flag/lk.png" }],
  btn: [{ value: "+975", label: "+975", image: "/Flag/bt.png" }],
  aed: [{ value: "+971", label: "+971", image: "/Flag/ae.png" }]
}

const codeOptions = computed(() => {
  return phoneCodeMap[selectedCurrency.value?.value] || []
})

const selectedCode = ref(codeOptions.value[0])

watch(selectedCurrency, (newCurrency) => {
  codeOptions.value = phoneCodeMap[newCurrency.value] || []
  selectedCode.value = codeOptions.value[0] // auto select first option
})

const errorStore = useErrorStore();
const authStore = useAuthStore();

const currency = computed(() => localeStore.getTranslate('currency'))
const userName = computed(() => localeStore.getTranslate('userName'))
const phoneNumber = computed(() => localeStore.getTranslate('phoneNumber'))
const referCode = computed(() => localeStore.getTranslate('referCode'))
const optional = computed(() => localeStore.getTranslate('optional'))
const email = computed(() => localeStore.getTranslate('email'))
const password = computed(() => localeStore.getTranslate('password'))
const confirmPassword = computed(() => localeStore.getTranslate('confirmPassword'))

const submit = async () => {
  try {
    const formData = new FormData();
    formData.append('user_name', signupForm.value.user_name);
    formData.append('email', signupForm.value.email);
    formData.append('refer_code', String(signupForm.value.refer_code ?? ''));
    formData.append('password', String(signupForm.value.password ?? ''));
    formData.append('password_confirmation', String(signupForm.value.password_confirmation ?? ''));

    if(selectedCurrency){
      formData.append('currency',selectedCurrency.value.value);
    }
    if(selectedCode){
      formData.append('phone_number',selectedCode.value.value + signupForm.value.phone_number);
    }
    
    const response = await useApiFetch('/signup', {
      method: 'POST',
      body: formData,
    });

    const token = response.token;
    const user = response.user;

    if (token && user) {
      authStore.setToken(token);
      authStore.setUser(user);
    }

    await router.push('/');

  } catch (error) {
    console.log(error.message || error);
  }
};

</script>