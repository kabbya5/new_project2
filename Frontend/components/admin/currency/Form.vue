<template>
    <!-- Modal BackDrop  -->

    <div class="fixed bg-black/50 flex items-center justify-center z-50">  <!-- table-fixed  -->
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-[90%]" @click.stop>
            <div class="modal-header bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold"> Currency Code </h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm">
                <div class="grid grid-cols-12 gap-4 p-6">
                    <div class="form-group mb-3 col-span-12 md:col-span-4">
                        <label for="" class="text-lg"> Currency Code </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Currency Code" 
                            v-model="form.currency_code"
                            required
                            :class="errorStore.validationErrors.currency_code ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.currency_code" class="text-red-500">  {{ errorStore.validationErrors.currency_code[0] }} </p>
                    </div>

                    <div class="form-group mb-3 col-span-12 md:col-span-4">
                        <label for="" class="text-lg"> Name </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="En Name" 
                            v-model="form.english_name"
                            required
                            :class="errorStore.validationErrors.english_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.english_name" class="text-red-500">  {{ errorStore.validationErrors.english_name[0] }} </p>
                    </div>

        
                    <div class="form-group mb-3 col-span-12 md:col-span-4">
                        <label for="" class="text-lg"> BRL Rate </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Hindi Name" 
                            v-model="form.brl_rate"
                            required
                            :class="errorStore.validationErrors.brl_rate ? 'border-red-500' :'' ">
                        

                        <p v-if="errorStore.validationErrors.provider_id" class="text-red-500">  {{ errorStore.validationErrors.brl_rate[0] }} </p>
                    </div>

                 </div>

                 <div class="flex justify-end w-full mb-6 px-6">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useCurrencyStore } from '~/stores/currency';
    import { useErrorStore } from '~/stores/error';

    const currencyStore = useCurrencyStore();
    const errorStore = useErrorStore();
    
    const props = defineProps<{ currency_id: number | null }>();
    const emit = defineEmits(['close']);

    const form = ref<any> ({
        currency_code:'',
        english_name:'',
        brl_rate:'',
    });

    const submitForm = async() => {
        try{
            const formData = new FormData();
            formData.append('currency_code', form.value.currency_code);
            formData.append('english_name', form.value.english_name);
            formData.append('brl_rate', String(form.value.brl_rate ?? ''));
          
           if (props.currency_id) {
                await currencyStore.update(props.currency_id, formData);
            } else {
                await currencyStore.store(formData);
            }
            if(!errorStore.message){
                emit('close');
            }
            
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.currency_id) {
            const currency = currencyStore.findCurrency(props.currency_id);
            if(currency){
                form.value.currency_code = currency.currency_code ?? null;
                form.value.english_name = currency.english_name ?? null;
                form.value.bangla_name = currency.bangla_name ?? null;
                form.value.hindi_name = currency.hindi_name ?? null;
                form.value.brl_rate = currency.brl_rate ?? null;
            }
        }
    });

</script>

