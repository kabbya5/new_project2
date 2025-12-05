<template>

<footer class="">
    <div v-if="route.path == '/'" class="my-4 container mx-auto bg-red-900 dark:bg-green-900 p-3 lg:p-4">
        <div class="flex flex-wrap item-center">
            <div class="pr-4 min-w-fit">
                <h2 class="mb-2 text-[11px] uppercase text-white border-b border-gray-200"> Affiliates Program </h2>
            </div>
            <div class="pr-4 min-w-fit">
                <h2 class="mb-2 text-[11px] uppercase text-white border-b border-gray-200"> Responsible Gaming </h2>
            </div>
            <div class="pr-4 min-w-fit">
                <h2 class="mb-2 text-[11px] uppercase text-white border-b border-gray-200"> Security </h2>
            </div>
            <div class="pr-4 min-w-fit">
                <h2 class="mb-2 text-[11px] uppercase text-white border-b border-gray-200"> Privacy Policy </h2>  
            </div>

            <div class="pr-4 min-w-fit">
                <h2 class="mb-2 text-[11px] uppercase text-white border-b border-gray-200"> Frequently Asked Questions </h2>  
            </div>
        </div>
        <div class="text-gray-500 my-6">
            <div class="flex justify-between">
                <div class="col-san-1">
                    <p class="text-[14px] text-white"> Gaming Licence </p>
                    <p class="text-gray-200 text-[13px] my-1"> Playfiver  <br>
                        Anjouan Gaming
                    </p>
                </div>
                <div class="col-san-1">
                    <p class="text-[14px] text-white "> Official Brand Partner </p>
                    <p class="text-gray-200 text-[13px]  font-sm my-1"> New City </p>
                </div>
            </div>
            
            <p class="text-gray-200 text-[12px] font-sm mt-6 mb-2"> 
                <span class="inline-block mb-2 text-white text-[14px] lg:text-lg"> Luckbuzz99 Bangladesh- Country's Premium Betting & Casino </span>
                <br>
            
                Every casino brand can declare that they are offering premium betting and features, 
                but most of them cannot really live up to the literal meaning of the word 
                premium. Thus, just like how smart you are when playing your favorite game,
                you should also be attentive and keen in choosing the casino that you will 
                participate in. Amongst the choices, Luckbuzz99 is the newest and trusted 
                player in Bangladesh.
                <br> <br>

                A huge welcome bonus is an insufficient reason for you to make a 
                bet. One should have reliable 24/7 customer support, a quick and 
                way to deposit and withdraw, and a user-friendly interface, 
                to name a few. Some of the best casino brands out there are Jeetbuzz, 
                Mega Casino World, Fancywin, and Baji Live. 

                <br>
                <br>
                With regard to security, gamblers must be very cautious and mindful
                of the security measures that a casino has. You should keep in
                mind that in order for you to play, you have to declare your personal and financial information, 
                both of which are private and sensitive data. Luckbuzz99 has the most advanced and reputable security measures, 
                as you do not want your data to be compromised.

                <br>
                <br>

                Even if your friends referred to a certain gambling site that they
                truly enjoy, you should still be cautious about the site’s reputation,
                especially its legality. Before you start betting, you should
                determine if the casino has a working license, which is a basic 
                component of a casino brand.

                <br>
                <br>

                Out of all the numerous payment methods that are available at Luckbuzz99 Bangladesh: BKash, Nagad,
                OKWallet, Rocket, Surecash, and CryptoCurrency, choosing one is definitely easy. 
                You can select the one that is more convenient and readily available in your area. Well, 
                it is not even a surprise if you have more than one of these.

                <br>
                <br>

                A world-class gaming experience is what all gamblers are up to. 
                With this, advanced technological features are being added to every 
                casino to make sure that brands like Luckbuzz99 Bangladesh are living up 
                to their “premium” branding. Checkout Luckbuzz99 promotions 
                section to know more!

            </p>

            <div class="my-8 text-white">
                <NuxtImg src="logo.png" class="w-32" />
                <p class="my-1 text-md text-white"> Best online platform </p>
                © {{ new Date().getFullYear() }} Luckbuzz99. All rights reserved.
            </div>
        </div>
    </div>

    <!-- bottom footer -->

    <div v-if="!authStore.token && showLoginBody" class="fixed bottom-0 left-0 w-full bg-red-900 dark:bg-green-900 z-50">
        <div class="flex md:hidden item-center justify-between">
            <NuxtLink class="text-center w-full text-white py-2 text-[16px] tracking-[1px]" 
                v-for="(item,index) in responsiveNavItems" :key="index" :to="item.link"
                :class="index == 0 ? 'bg-blue-800' : 'bg-green-800'"> 
                {{ item[name] }}
            </NuxtLink>
        </div>
    </div>

    <!-- Bottom Navigation -->
    <div class="v-else md:hidden fixed bottom-0 left-0 w-full bg-red-900 z-50 dark:bg-green-900 shadow-lg">
        <div class="flex justify-around p-3 max-w-md mx-auto">
            <NuxtLink to="/" class="flex flex-col items-center text-white">
                <i class="fas fa-home text-lg"></i>
                <span class="text-xs mt-1">Home</span>
            </NuxtLink>

            <NuxtLink to="/profile" class="flex flex-col items-center text-white">
                <i class="fas fa-user text-lg"></i>
                <span class="text-xs mt-1">Profile</span>
            </NuxtLink>

            <NuxtLink to="/profile/manual-deposit" class="flex flex-col items-center text-white">
                <i class="fas fa-wallet text-lg"></i>
                <span class="text-xs mt-1">Deposit</span>
            </NuxtLink>

            <NuxtLink to="/profile/manual-withdrow" class="flex flex-col items-center text-white">
                <i class="fas fa-wallet text-lg"></i>
                <span class="text-xs mt-1">Withdraw</span>
            </NuxtLink>

            <NuxtLink to="/promotion" class="flex flex-col items-center text-white">
                <i class="fa-solid fa-rectangle-ad"></i>
                <span class="text-xs mt-1">Promotion</span>
            </NuxtLink>
        </div>
    </div>
</footer>
</template>

<script setup lang="ts">

import { useLocaleStore } from '~/stores/locale';
import {useAuthStore} from '~/stores/auth';

const localeStore = useLocaleStore();
const authStore = useAuthStore();
const route = useRoute()


const responsiveNavItems = [
  {
    english_name:'Login',
    bangla_name: 'লগইন',
    hindi_name: 'रजिस्टर',
    link:'/login',
  },

  {
    english_name: 'Sign Up',
    bangla_name: 'সাইন আপ',
    hindi_name: 'साइन अप',
    link: '/register',
  }

]

const name = computed(() => localeStore.getTranslate('name'))

const showLoginBody = ref<boolean>(true);
const guestRoutes = ['/login', '/register']

watch(
  () => route.path,
  (newPath) => {
    showLoginBody.value = !guestRoutes.includes(newPath)
  },
  { immediate: true }
)

</script>


