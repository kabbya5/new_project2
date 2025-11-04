import {defineStore} from 'pinia';
import { useLoadingStore } from '~/stores/loading';
import type { DepositNumber} from "~/types/depositNumber";

export const useDepositNumberStore = defineStore('deposit_number',{
    state:() =>({
        deposit_numbers:[] as DepositNumber[],
    }),

    actions:{
        async index(){
            const loading = useLoadingStore();
            loading.start('deposit_number');

            try{
                const response :any = await useApiFetch('/admin/deposit_numbers/index');
                if(response.data){
                    this.deposit_numbers = response.data;
                }
            }catch(error){
                alert(error);
            }finally{
                loading.stop('deposit_number');
            }
        },

        async store(labelForm:FormData|DepositNumber){
            try{
                const response :any = await useApiFetch('/admin/deposit_numbers/store',{
                    method:'POST',
                    body:labelForm,
                });

                if (response && response.data) {
                    this.deposit_numbers.push(response.data);
                }
            }catch(error){
                console.log(error);
            }
        },

        async update(labelId:number, labelForm:FormData|DepositNumber){
            if (labelForm instanceof FormData) {
                labelForm.append('_method', 'PUT');
            }
            try {
                const response:any = await useApiFetch(`/admin/deposit_numbers/update/${labelId}`, {
                    method: 'POST', 
                    body: labelForm,
                });
    
                if (response && response.data) {
                    const index = this.deposit_numbers.findIndex(p => p.id === labelId);
                    if (index !== -1) {
                        this.deposit_numbers[index] = response.data;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        find(number_id: number): DepositNumber | undefined {
            return this.deposit_numbers.find(number => number.id === number_id);
        },
        
        async delete(number_id: number) {
            try {
                const response: any = await useApiFetch(`/admin/deposit_numbers/delete/${number_id}`, {
                    method: 'DELETE',
                });

                if (response && response.success) {
                    // Remove the deleted text from state
                    this.deposit_numbers = this.deposit_numbers.filter(number => number.id !== number_id);
                    return true;
                }

                return false;
            } catch (error) {
                console.error(error);
                return false;
            }
        }
    },
})
