<template>
  <!-- Modal Backdrop -->
  <div
    class="fixed w-full top-0 py-4 left-0 bg-black/50 flex items-top justify-center z-50"
    @click="$emit('close')"
  >
    <div
      class="bg rounded-lg shadow-lg w-full max-w-lg mx-4 animate-fadeIn"
      @click.stop
    >
      <!-- Modal Header -->
      <div
        class="modal-header bg-card py-3 px-6 flex items-center justify-between rounded-t-lg"
      >
        <h2 class="text-xl font-bold">
          Transaction Number
        </h2>
        <button
          @click="$emit('close')"
          class="bg-red-500 hover:bg-red-600 w-[30px] h-[30px] flex items-center justify-center rounded-full text-white transition"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm">
        <div class="grid grid-cols-12 gap-4 px-6 py-4">
            <div class="form-group col-span-12">
                <label for="text" class="text-lg font-semibold dark:text-white"
                    > Owner Name  </label
                >
                <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Owner name"
                    v-model="form.owner_name"
                    required
                    :class="errorStore.validationErrors.owner_name ? 'border-red-500' : ''"
                />

                <p v-if="errorStore.validationErrors.owner_name" class="text-red-500 text-sm mt-1">
                    {{ errorStore.validationErrors.owner_name[0] }}
                </p>
            </div>

            <div class="form-group col-span-12">
                <label for="text" class="text-lg font-semibold dark:text-white"> Provider </label>
                <select type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Select Provider"
                    v-model="form.provider" required
                    :class="errorStore.validationErrors.provider ? 'border-red-500' : ''"> 
                   
                    <Option value="bkash"> Bkash </Option>
                    <option value="nagad"> Nagot </option>

                </select>

                <p v-if="errorStore.validationErrors.provider" class="text-red-500 text-sm mt-1">
                    {{ errorStore.validationErrors.provider[0] }}
                </p>
            </div>

            <div class="form-group col-span-12">
                <label for="text" class="text-lg font-semibold dark:text-white"> Transaction Type </label>
                <select type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Select Transaction Type"
                    v-model="form.transaction_type" required
                    :class="errorStore.validationErrors.transaction_type ? 'border-red-500' : ''"> 
                   
                    <Option value="deposit"> Deposite </Option>
                    <option value="withdraw"> Withdraw </option>

                </select>

                <p v-if="errorStore.validationErrors.transaction_type" class="text-red-500 text-sm mt-1">
                    {{ errorStore.validationErrors.transaction_type[0] }}
                </p>
            </div>

            <div class="form-group col-span-12">
                <label for="text" class="text-lg font-semibold dark:text-white"
                    > Number </label
                >
                <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="phone number"
                    v-model="form.phone_number"
                    required
                    :class="errorStore.validationErrors.phone_number ? 'border-red-500' : ''"
                />

                <p v-if="errorStore.validationErrors.phone_number" class="text-red-500 text-sm mt-1">
                    {{ errorStore.validationErrors.phone_number[0] }}
                </p>
            </div>

            <div class="form-group col-span-12">
                <label for="text" class="text-lg font-semibold dark:text-white"
                    > Status </label
                >
                <select type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Select status"
                    v-model="form.status" required
                    :class="errorStore.validationErrors.status ? 'border-red-500' : ''"
                > 
                    <Option value="active"> Active </Option>
                    <option value="inactive"> Inactive </option>

                </select>

                <p v-if="errorStore.validationErrors.status" class="text-red-500 text-sm mt-1">
                    {{ errorStore.validationErrors.status[0] }}
                </p>
            </div>

            <div class="flex justify-end mt-3 col-span-12">
                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition">
                    Save
                </button>
            </div>
        </div>
      </form>
    </div>
  </div>
</template>


<script setup lang="ts">
    import type { DepositNumber } from '~/types/depositNumber';
    import { useDepositNumberStore } from '~/stores/depositNumber';
    import { useErrorStore } from '~/stores/error';

    const numberStore = useDepositNumberStore();
    const errorStore = useErrorStore();
    
    const props = defineProps<{ number_id: number | null }>();
    const emit = defineEmits(['close']);

    const form = ref<DepositNumber>({
        id: null,
        owner_name: '',   
        provider: 'bkash',
        status:'active',
        transaction_type:'deposit',
        phone_number:'',
    });

    const submitForm = async() => {
        try{
            const formData = new FormData();
            if (form.value.owner_name !== null) formData.append('owner_name', form.value.owner_name);
            if (form.value.provider !== null) formData.append('provider', String(form.value.provider));
            if (form.value.transaction_type !== null) formData.append('transaction_type', String(form.value.transaction_type));
            if (form.value.status !== null) formData.append('status', String(form.value.status));
            if (form.value.phone_number !== null) formData.append('phone_number', String(form.value.phone_number));
           
            if (props.number_id) {
                await numberStore.update(props.number_id, formData);
            } else {
                await numberStore.store(formData);
            }
            if(!errorStore.message){
                emit('close');
            }
            
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.number_id) {
            const text = numberStore.find(props.number_id);
            if(text){
                form.value = text;
                console.log(text);
            }
        }
    });

</script>

