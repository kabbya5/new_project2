<template>
    <div class="pt-2">
        <div class="max-w-3xl mx-auto px-6">
            <!-- Card -->
            <div class="shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div class="bg-red-700 text-white px-6 py-2">
                    <div class="flex items-center justify-between">
                        <div class="text-xl font-semibold">BDT {{ amount }}</div>
                        <div class="text-sm mt-1">কম বা বেশি ক্যাশআউট করবেন না</div>
                    </div>
                </div>

                <!-- Notice -->
                <div class="py-2">
                    <div class="text-red-600 bg-red-50 border border-red-100 rounded-md p-3 text-sm">
                        আপনি যদি টাকার পরিমাণ পরিবর্তন করেন {{ amount }}, আপনি ক্রেডিট পেতে সক্ষম হবেন না।
                    </div>
                </div>

                <!-- Wallet + Provider Row -->
                <div class="grid grid-cols-12 my-2">
                    <!-- Wallet box (left two columns on md) -->
                    <div class="col-span-8">
                        <label class="text-sm font-semibold">Wallet No*</label>
                        <div class="text-xs">এই <span class="uppercase"> {{ provider }} </span> নাম্বারে শুধুমাত্র ক্যাশআউট গ্রহণ করা হয়</div>

                    </div>

                    <!-- Provider card -->
                    <div class="col-span-4 pl-2">
                        <div class="flex items-center gap-3 shadow">
                            <div class="w-12 h-12 flex items-center justify-center rounded-full shrink-0">
                                <!-- placeholder logo -->
                                <NuxtImg v-if="provider == 'nagad'" src="images/logo/nagad.jpg" alt="NAGAD" class="w-9 h-9 object-contain"/>
                                <NuxtImg v-else src="images/logo/bkash.jpg" alt="bkash" class="w-9 h-9 object-contain"/>
                            </div>
                            <div>
                                <div class="text-sm font-semibold"> <span class="uppercase"> {{ provider }} </span> Deposit</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 flex items-center w-full">
                    <input type="text" readonly :value="phone_number" ref="phoneInput"
                        class="px-4 w-full py-2 border rounded-md bg-gray-100 text-gray-800 focus:outline-none" />

                    <button @click="copyPhoneNumber" class="px-3 py-2 bg-white border rounded-md shadow-sm hover:bg-gray-50">
                        <!-- clipboard icon (simple) -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-700" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6M9 16h6M4 7h16M5 7v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V7" />
                        </svg>
                    </button>
                </div>

                <!-- TrxID input -->
                <div class="py-5">
                    <label class="block text-sm font-semibold mb-2">ক্যাশআউটের TrxID নম্বরটি লিখুন <span class="text-white">(প্রয়োজন)</span></label>

                    <form @submit.prevent="submitForm" class="space-y-4 py-2">
                        <div>
                            <input  type="text" placeholder="TrxID অবশ্যই পূরণ করতে হবে"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-200" 
                            v-model="form.trxid"
                            :class="errorStore.validationErrors.trxid ? 'border-red-500' :'' ">

                            <p v-if="errorStore.validationErrors.provider_id" class="text-red-500">  {{ errorStore.validationErrors.provider_id[0] }} </p>
                        </div>

                        <div class="form-group">
                            <label for="" class="block text-sm font-semibold mb-2"> স্ক্রিনশট </label>
                            <div class="flex">
                                <input type="file" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-200" 
                                    placeholder="Enter Logo Path"
                                    @change="handelImageUpload" 
                                    :class="errorStore.validationErrors.image ? 'border-red-500' :'' ">

                                <img v-if="imagePreview" :src="imagePreview" alt="" class="ml-2 w-[36px] h-[40px]">
                            </div>
                            
                            <p v-if="errorStore.validationErrors.image" class="text-red-500">  {{ errorStore.validationErrors.image[0] }} </p>
                            
                        </div>

                        <div class="flex items-center justify-center mt-2">
                            <button type="submit" class="px-8 py-2 border rounded-md bg-white text-black hover:bg-gray-50">নিশ্চিত</button>
                        </div>

                    </form>

                    <!-- small footnote -->
                    <div class="mt-4 text-xs text-white leading-relaxed">
                        সতর্কতা: লেনদেন আইডি ভেরিফিকেশন পুরন করতে হবে, অন্যথায্য কোন ব্যবস্থা নেওয়া হবে। অনুগ্রহ করে নিশ্চিত হন যে আপনি NAGAD deposit ওয়ালেটে সঠিক TrxID দিয়েছেন।
                    </div>

                    <p class="mt-4 white font-medium">
                        ⏱️ ২০ মিনিটের বেশি সময় লাগলে দ্রুত যোগাযোগ করুন।
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">

import { useErrorStore } from '~/stores/error';
const errorStore = useErrorStore();

const router = useRouter();
const route = useRoute();
const phone_number = route.query.phone_number;
const provider = route.query.provider;
const amount = Number(route.query.amount).toFixed(2);
const phoneInput = ref(null)

const form = ref<{
  trxid: string | null,
  image: File | null
}>({
  trxid: null,
  image: null,
})

const imagePreview = ref<string | null>(null);

const handelImageUpload = (event:Event) =>{
    const file = (event.target as HTMLInputElement).files?.[0]
    if(file){
        form.value.image = file;
        imagePreview.value = URL.createObjectURL(file);
    }
}

function copyPhoneNumber() {
  if (phoneInput.value) {
    navigator.clipboard.writeText(phoneInput.value.value)
      .then(() => {
        alert('Phone number copied!')
      })
      .catch(err => {
        console.error('Copy failed:', err)
      })
  }
}

const submitForm = async () => {
  try {
    if (!form.value.trxid) {
      alert('TrxID অবশ্যই পূরণ করতে হবে!')
      return
    }

    const formData = new FormData()
    formData.append('trxid', form.value.trxid)

    if (form.value.image) {
      formData.append('image', form.value.image)
    }

    // Provider & Amount
    if (provider) formData.append('provider', provider)
    formData.append('amount', amount);

    // Example POST request using axios
    const response = await useApiFetch(`/manualy/deposit/store`,{
        method:'POST',
        body:formData,
    });

    if (response.status === 1) {
      alert('Deposit submitted successfully!')
      router.push('/profile')
      
    } else {
      alert('Submission failed!')
    }

  } catch (error: any) {
    console.error(error)
    alert('Error: ' + (error.message || error))
  }
}
</script>