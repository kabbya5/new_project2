<template>
    <!-- Modal BackDrop  -->

    <div class="fixed w-full top-8 left-0 bg-black/50 flex items-center justify-center z-50">  <!-- table-fixed  -->
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-md" @click.stop>
            <div class="modal-header bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold"> Add Transaction </h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm">
                <div class="grid grid-cols-12 gap-4 p-4">
                    <div class="form-group mb-3 col-span-12">
                        <label for="" class="text-lg"> User Name </label>
                        <input type="text" class="w-full border mt-2 px-2 py-2 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="User Name" 
                            v-model="form.user_name"
                            required
                            :class="errorStore.validationErrors.user_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.user_name" class="text-red-500">  {{ errorStore.validationErrors.user_name[0] }} </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Transaction Type  </label>
                        <select class="w-full border mt-2 px-2 py-2 dark:bg-gray-700 dark:text-white focus:outline-none">
                            <option selected> {{ props.type }} </option>
                        </select>
                    </div>

                    <div class="form-group mb-3 col-span-12">
                        <label for="" class="text-lg"> Transaction Code </label>
                        <input type="text" class="w-full border mt-2 px-2 py-2 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Transaction Code" 
                            v-model="form.transaction_code"
                            required
                            :class="errorStore.validationErrors.transaction_code ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.transaction_code" class="text-red-500">  {{ errorStore.validationErrors.transaction_code[0] }} </p>
                    </div>

                    <div class="form-group mb-3 col-span-12">
                        <label for="" class="text-lg"> Amount </label>
                        <input type="number" step="any" class="w-full border mt-2 px-2 py-2 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="En Name" 
                            v-model="form.amount"
                            required
                            :class="errorStore.validationErrors.amount ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.amount" class="text-red-500">  {{ errorStore.validationErrors.amount[0] }} </p>
                    </div>
                </div>

                <div class="flex justify-end w-full mb-6 px-4">
                    <button v-if="!loading.isLoading('transaction')" type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    <button v-else="!loading.isLoading('transaction')" type="button" class="bg-blue-500 text-white px-4 py-2 rounded"> Loading </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">

    import type { TransactionForm } from '~/types/transactionForm';

    import { useErrorStore } from '~/stores/error';
    import {useTransactionStore} from '~/stores/transaction';
    import { useLoadingStore } from '~/stores/loading';

    const errorStore = useErrorStore();
    const transactionStore = useTransactionStore();
    const loading = useLoadingStore();

    const props = defineProps<{
        transactionId: number | null
        type:'string' 
    }>();

    const emit = defineEmits(['close']);

    const form = ref<TransactionForm>({
        user_name: '',
        type: null,
        amount: 0,
        transaction_code: null
    });

    const submitForm = async() => {
        try{
            const formData = new FormData()
            formData.append('user_name', form.value.user_name)
            formData.append('type', props.type  ?? '')
            formData.append('amount', form.value.amount.toString())
            formData.append('transaction_code', form.value.transaction_code ?? '')

           if (props.transactionId) {
                await transactionStore.update(props.transactionId, formData)
            } else {
                await transactionStore.store(formData)
            }
            if(!errorStore.message){
                emit('close');
            }
            
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.transactionId) {
            const tansaction = transactionStore.findTransaction(props.transactionId);
            if(tansaction){
                form.value.user_name = tansaction.user_nane ?? null;
                form.value.amount = tansaction.amount ?? 0;
                form.value.transaction_code = tansaction.order_sn ?? null;
            }
        }
    });

</script>
