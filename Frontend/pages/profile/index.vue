

<template>
  <div class="flex item-center justify-center py-4">
    <div class="w-full md:w-[400px] bg-red-900 dark:bg-green-900">
        <div class="">
            <!-- Header -->
            <div class="flex justify-between items-center mb-4 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                <h1 class="text-2xl font-bold text-white">Profile</h1>
                <div class="relative">
                    <!-- <button class="p-2 rounded-full bg-white dark:bg-gray-800 shadow">
                        <i class="fas fa-bell text-gray-600 dark:text-gray-300"></i>
                    </button> -->
                    <!-- <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">52</span> -->
                </div>
            </div>

            <!-- Profile Card -->
            <div class="card rounded-2xl px-4 mb-4">
                <div class="flex items-center mb-6">
                    <div v-if="authStore.user?.image_url" class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
                        <NuxtImg :src="authStore.user?.image_url" class="rounded-full h-16 w-16"></NuxtImg>
                    </div>

                    <div v-else class="h-16 w-16 rounded-full bg-gradient-to-r from-purple-400 to-pink-500 flex items-center justify-center text-white text-2xl font-bold">
                      {{ authStore.user?.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <div class="ml-4">
                        <h2 class="text-xl font-bold text-white"> {{ authStore.user?.name }} </h2>
                        <p class="text-whtie"> Member since: {{ authStore.user?.created_at }} </p>
                    </div>
                </div>

                <!-- Withdrawal & Deposit -->
                <div class="flex justify-between mb-4">

                    <NuxtLink to="/profile/manual-withdrow" class="flex-1 mr-2 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-arrow-up mr-2"></i> Withdrawal
                    </NuxtLink>

                    <NuxtLink to="/profile/manual-deposit" class="flex-1 ml-2 py-2 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-lg font-medium flex items-center justify-center">
                        <i class="fas fa-arrow-down mr-2"></i> Deposit
                    </NuxtLink>

                </div>

                <!-- Wallet Info -->
                <div class="mb-2">
                    <h3 class="text-md font-semibold text-white mb-3">Main Wallet</h3>
                    <div class="bg-red-800 dark:bg-green-800 rounded-lg px-2 py-2 mb-2">
                        <div class="flex justify-between">
                            <span class="text-whtie">Balance</span>
                            <div @click="recallBalance" class="cursor-pointer">
                              <span v-if="!showAmount" class="font-mono text-white">*****</span>
                              <span v-else class="font-mono text-white">{{ authStore.user?.balance }}</span>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div v-if="authStore.user?.turnover" class="mb-4">
                    <div class="bg-red-800 dark:bg-green-800 rounded-lg px-2 py-2">
                        <div class="flex justify-between">
                            <span class="text-whtie"> Turn Over </span>
                            <div class="cursor-pointer">
                              <span  class="font-mono text-white">{{ authStore.user?.turnover}}</span>
                            </div>
                            
                        </div>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-lg font-semibold text-white"> Your Referral Link  <span class="text-green-500 text-sm"> (Code: {{ authStore.user?.refer_code }}) </span> </h3>
                        <button
                            @click="openModal = true"
                            class="bg-blue-600 text-white px-2 py-1 rounded-full hover:bg-blue-700 transition-all duration-200 shadow-sm active:scale-95"
                            title="Share"
                            >
                            <i class="fa-solid fa-share text-sm"></i>
                        </button>
                    </div>
                    
                    <div class="flex">
                        <input
                            v-if="referralUrl"
                            type="text"
                            :value="referralUrl"
                            readonly
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-l-lg bg-white text-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-200"
                        />
                        <button
                            v-if="referralUrl"
                            @click="copyRefer"
                            class="px-4 py-2 bg-blue-600 text-white rounded-r-lg border border-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-400"
                        >
                            Copy
                        </button>
                        <input
                            v-else
                            type="text"
                            value="No referral code available"
                            readonly
                            class="flex-1 px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed"
                        />
                    </div>
                </div>

                <!-- VIP Points -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-white mb-3">VIP Level</h3>
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 rounded-lg p-4">
                        <div class="flex justify-between text-white">
                            <span>Current deposit ({{ authStore.user?.deposit_amount }})</span>
                            <span class="font-mono"> {{ authStore.user?.level?.name }} </span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2 dark:bg-gray-700">
                            <div class="bg-yellow-400 h-2.5 rounded-full" :style="`width: ${authStore.user?.level_complete}%`"></div>
                        </div>
                    </div>
                </div>

                <!-- Menu Options -->
                <div class="space-y-3">

                    <button v-if="authStore.user?.deposit_amount && !authStore.user?.today_bonus" @click="takeBonus(authStore.user?.level?.daily_bonus)" class="flex w-full items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fa-solid fa-gift text-orange-500 mr-3"></i>
                            <span class="text-white"> Take Bonus ({{authStore.user?.level?.daily_bonus}}) </span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </button>

                    <NuxtLink to="/profile/level" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fa-solid fa-crown text-yellow-500 mr-3"></i>
                            <span class="text-white">VIP Level</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>

                    <NuxtLink to="profile/edit" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-user text-blue-500 mr-3"></i>
                            <span class="text-white">Personal Info</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>
                    
                    <NuxtLink to="profile/bonus" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fa-solid fa-coins text-green-500 mr-3"></i>
                            <span class="text-white"> Refer Bonus </span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>

                    <NuxtLink to="profile/transaction" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-receipt text-orange-500 mr-3"></i>
                            <span class="text-white">Transaction Records</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>

                    <NuxtLink to="/profile/betting" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fa-solid fa-bars-staggered text-emerald-500 mr-3"></i>
                            <span class="text-white"> Betting Records</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>

                    <NuxtLink to="profile/changePassword" class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span class="text-white"> Login & Security </span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </NuxtLink>

                    <div class="flex items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-purple-500 mr-3"></i>
                            <span class="text-white">Verification</span>
                        </div>
                        <i class="fas fa-chevron-right text-gray-400"></i>
                    </div>

                    <button @click="logout()" class="inline-flex w-full items-center justify-between p-3 bg-red-800 dark:bg-green-800 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt text-green-500 mr-3"></i>
                            <span class="text-white"> Logout </span>
                        </div>
                        <i class="fa-solid fa-sign-out text-gray-400"></i>
                    </button>   
                </div>
            </div>

            <div
              v-if="openModal"
              class="fixed left-0 top-0 w-full h-screen bg-black/50 flex items-center justify-center z-50"
              @click.self="openModal = false"
            >
              <div class="bg-white dark:bg-gray-800 px-2 py-3 rounded-2xl shadow-lg max-w-md w-full">
                <h2 class="text-lg text-left font-semibold mb-4 text-center text-gray-800 dark:text-gray-100">
                  Share this link
                </h2>

                <div class="flex item-senter flex-wrap">
                  <a 
                    v-for="item in socials"
                    :key="item.id"
                    :href="generateShareUrl(item.id)"
                    target="_blank"
                    class="px-2 py-2 mr-2 flex flex-col items-center rounded-xl text-white hover:opacity-90 transition"
                    :class="{
                      'bg-blue-600': item.id === 'facebook',
                      'bg-sky-500': item.id === 'twitter',
                      'bg-blue-400': item.id === 'telegram',
                      'bg-green-500': item.id === 'whatsapp',
                      'bg-blue-500': item.id === 'messenger',
                    }"
                  >
                    <i :class="item.icon" class="text-2xl"></i>
                    <span class="text-sm">{{ item.label }}</span>
                  </a>
                </div>

            <!-- Close button -->
            <button
              @click="openModal = false"
              class="mt-6 block w-full bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-100 rounded-lg py-2 hover:bg-gray-400 dark:hover:bg-gray-600 transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">

useHead({
  title: 'LuckBuzz99 - Play Multiple Betting Games Online',
  meta: [
    { name: 'description', content: 'LuckBuzz99 is your ultimate platform to play multiple online betting games safely and enjoyably.' },
    { property: 'og:title', content: 'LuckBuzz99 - Play Multiple Betting Games Online' },
    { property: 'og:description', content: 'LuckBuzz99 is your ultimate platform to play multiple online betting games safely and enjoyably.' },
    { property: 'og:image', content: 'https://luckbuzz99.com/logo.png'}, // Replace with your real image
    { property: 'og:url', content: 'https://luckbuzz99.com' },
    { property: 'og:type', content: 'website' },
    
    { name: 'twitter:card', content: 'summary_large_image' },
    { name: 'twitter:title', content: 'LuckBuzz99 - Play Multiple Betting Games Online' },
    { name: 'twitter:description', content: 'LuckBuzz99 is your ultimate platform to play multiple online betting games safely and enjoyably.' },
    { name: 'twitter:image', content: 'https://luckbuzz99.com/og-image.jpg' }, // Replace with your real image
  ],
})

definePageMeta({
  middleware: 'auth',
})

import { useAuthStore } from '~/stores/auth';
import { useTransactionStore } from '~/stores/transaction';
import {useLabelStore} from '~/stores/label';

const transactionStore = useTransactionStore();
const authStore = useAuthStore();
const {handleLogout} = authStore;
const lableStore = useLabelStore();

// Socials
const socials = ref([
  { id: 'facebook', label: 'Facebook', icon: 'fa-brands fa-facebook-f' },
  { id: 'messenger', label: 'Messenger', icon: 'fa-brands fa-facebook-messenger' },
  { id: 'twitter', label: 'Twitter', icon: 'fa-brands fa-x-twitter' },
  { id: 'telegram', label: 'Telegram', icon: 'fa-brands fa-telegram' },
  { id: 'whatsapp', label: 'WhatsApp', icon: 'fa-brands fa-whatsapp' },
])

// Generate share URL
const generateShareUrl = (platform) => {
  const url = encodeURIComponent(referralUrl.value)
  const text = encodeURIComponent("Join me using my referral link!")

  switch (platform) {
    case 'facebook':
      return `https://www.facebook.com/sharer/sharer.php?u=${url}`
    case 'messenger':
      return `fb-messenger://share/?link=${url}`
    case 'twitter':
      return `https://twitter.com/intent/tweet?url=${url}&text=${text}`
    case 'telegram':
      return `https://t.me/share/url?url=${url}&text=${text}`
    case 'whatsapp':
      return `https://api.whatsapp.com/send?text=${text}%20${url}`
    default:
      return '#'
  }
}

const baseUrl = ref('');
const openModal = ref(false);

const referralUrl = computed(() =>
  authStore.user?.refer_code ? `${baseUrl.value}/register?ref=${authStore.user.refer_code}` : ''
)

const showAmount = ref<boolean>(false);

onMounted(() => {
  baseUrl.value = window.location.origin;
  authStore.recallUser();
})

const recallBalance = () =>{
    authStore.recallUser();
    showAmount.value = !showAmount.value;
}

const takeBonus = async (bonus:number) =>{
  const data :any = await useApiFetch('/user/take/bonus/' + bonus);
  if(data.status == 1){
    recallBalance();
  }
}



const copyRefer = async () => {
  if (referralUrl.value) {
    await navigator.clipboard.writeText(referralUrl.value)
    alert('Referral URL copied!')
  }
}

const router = useRouter();

const logout = async () => {
  await handleLogout(); 
  router.push('/');   
}
</script>

