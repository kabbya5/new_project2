

<template>
  <div class="w-full max-w-[500px] mx-auto min-h-full bg-gray-100 dark:bg-gray-900 p-4">
    <!-- Card -->
    <div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-6">

      <div class="header text-center w-full">
          <h2 class=""> Profile </h2>
      </div>
      <!-- Top section -->
      <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">
              {{ authStore.user?.user_name}}
            </h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Level: {{ authStore.user?.level ?? 1 }}
            </p>
        </div>
        <div class="text-right">
          <p class="text-lg font-semibold uppercase text-green-600 dark:text-green-400">
            {{ authStore.user?.currency}}
            {{ authStore.user?.balance ?? 0 }}
          </p>
        </div>
      </div>

      <!-- Actions -->
      <div class="grid grid-cols-2 gap-4 mb-6">
        <NuxtLink to="/profile/deposite"
          class="w-full text-center py-2 bg-green-500 hover:bg-green-600 text-white rounded-xl shadow"
        >
          Deposit
        </NuxtLink>
        <NuxtLink to="/profile/withdrow"
          class="w-full text-center py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl shadow"
        >
          Withdraw
        </NuxtLink>
      </div>

      <div class="flex items-center justify-between mb-6">
        <h2 class="text-lg font-semibold">Member Center</h2>
      </div>

      <div class="grid grid-cols-4">
        <div class="col-span-1 border border-gray-300 dark:border-gray-600 py-3 px-2">
          <button @click="getDailyBalance" class="group relative flex flex-col items-center rounded-xl">
            <div class="relative">
              <div class="w-16 h-16 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 ring-1 ring-amber-100 dark:ring-amber-800">
                <i class="fa-solid fa-trophy text-amber-600 dark:text-amber-300 text-xl"></i>
              </div>
              <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-500 rounded-full"> 1 </span>
            </div>
            <span class="text-sm text-center"> Reward Center </span>
          </button>
        </div>

        <div class="col-span-1 border border-gray-300 dark:border-gray-600 py-3 px-2">
          <button @click="getDailyBalance" class="group relative flex flex-col items-center rounded-xl">
            <div class="relative">
              <div class="w-16 h-16 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 ring-1 ring-amber-100 dark:ring-amber-800">
                <i class="fa-solid fa-trophy text-amber-600 dark:text-amber-300 text-xl"></i>
              </div>
            </div>
            <span class="text-sm text-center"> Deposite Record </span>
          </button>
        </div>

        <div class="col-span-1">
          <button @click="getDailyBalance" class="group relative flex flex-col items-center gap-3 p-4 rounded-xl hover:shadow-lg transition-shadow duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
            <div class="relative">
              <div class="w-16 h-16 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 ring-1 ring-amber-100 dark:ring-amber-800">
                <i class="fa-solid fa-trophy text-amber-600 dark:text-amber-300 text-xl"></i>
              </div>
              <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-500 rounded-full"> {{ 10.2 }} </span>
            </div>
            <span class="text-sm text-center"> Deposit Recored  </span>
          </button>
        </div>

        <div class="col-span-1">
          <button @click="getDailyBalance" class="group relative flex flex-col items-center gap-3 p-4 rounded-xl hover:shadow-lg transition-shadow duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
            <div class="relative">
              <div class="w-16 h-16 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 ring-1 ring-amber-100 dark:ring-amber-800">
                <i class="fa-solid fa-trophy text-amber-600 dark:text-amber-300 text-xl"></i>
              </div>
              <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-500 rounded-full"> {{ 10.2 }} </span>
            </div>
            <span class="text-sm text-center"> Withdraw Record  </span>
          </button>
        </div>

        <div class="col-span-1">
          <button @click="getDailyBalance" class="group relative flex flex-col items-center gap-3 p-4 rounded-xl hover:shadow-lg transition-shadow duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-indigo-500">
            <div class="relative">
              <div class="w-16 h-16 rounded-full flex items-center justify-center bg-amber-50 dark:bg-amber-900/30 ring-1 ring-amber-100 dark:ring-amber-800">
                <i class="fa-solid fa-trophy text-amber-600 dark:text-amber-300 text-xl"></i>
              </div>
              <span class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-0.5 text-xs font-semibold leading-none text-white bg-red-500 rounded-full"> {{ 10.2 }} </span>
            </div>
            <span class="text-sm text-center"> My Account  </span>
          </button>
        </div>
      </div>


      <!-- Referral -->
      <div class="mb-6">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
          Referral
        </h2>
        <div class="flex items-center gap-2">
          <input
                type="text"
                readonly
                :value="referralUrl"
                class="flex-1 px-3 py-2 rounded-lg border dark:border-gray-600 dark:bg-gray-700 text-gray-800 dark:text-gray-100"
                />
                <button
                @click="copyRefer"
                class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg shadow"
                >
                Copy
                </button>
        </div>
      </div>

      <!-- Social Media Search -->
      <div>
        <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">
          Social Media
        </h2>
        <input
          type="text"
          v-model="searchQuery"
          placeholder="Search social..."
          class="w-full px-3 py-2 mb-3 rounded-lg border dark:border-gray-600 dark:bg-gray-700 text-gray-800 dark:text-gray-100"
        />
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
          <div
            v-for="s in filteredSocials"
            :key="s.id"
            class="flex items-center justify-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-200 dark:hover:bg-gray-600"
          >
            <span :class="s.icon" class="text-xl"></span>
            <span>{{ s.label }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useAuthStore } from '~/stores/auth'

const authStore = useAuthStore()

// Social search
const searchQuery = ref('')
const socials = ref([
  { id: 'facebook', label: 'Facebook', icon: 'i-fa6-brands:facebook' },
  { id: 'twitter', label: 'Twitter', icon: 'i-fa6-brands:x-twitter' },
  { id: 'telegram', label: 'Telegram', icon: 'i-fa6-brands:telegram' },
  { id: 'whatsapp', label: 'Whatsapp', icon: 'i-fa6-brands:whatsapp' },
])

const filteredSocials = computed(() =>
  socials.value.filter(s =>
    s.label.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
)

// ✅ SSR safe referral link
const baseUrl = ref('')
onMounted(() => {
  baseUrl.value = window.location.origin
})

const referralUrl = computed(() =>
  authStore.user?.refer_code ? `${baseUrl.value}/register?ref=${authStore.user.refer_code}` : ''
)

const copyRefer = async () => {
  if (referralUrl.value) {
    await navigator.clipboard.writeText(referralUrl.value)
    alert('Referral URL copied!')
  }
}
</script>

