import {defineStore} from 'pinia';
import type { PromotionForm } from '~/types/promotionForm';

import { useLoadingStore } from '~/stores/loading';

export const usePromotionStore = defineStore('promotion',{
    state:()=>({
        promotions:[] as PromotionForm[],
    }),

    actions:{
        async store(PromotionForm:FormData | PromotionForm){
            try {
                const response = await useApiFetch(`/admin/promotions/store`,{
                    method:'POST',
                    body:PromotionForm,
                });

                if (response && response.promotion) {
                    this.promotions.push(response.promotion);
                }
            }catch(error){
                alert(error);
            }
        },

        async update(promotionId: number, PromotionForm: FormData | PromotionForm) {
            if (PromotionForm instanceof FormData) {
                PromotionForm.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/promotions/update/${promotionId}`, {
                    method: 'POST', // অথবা 'PUT' যদি তোমার API তে PUT লাগে
                    body: PromotionForm,
                });

                if (response && response.promotion) {
                    // store এর ভিতরে পুরোনো promotion replace করা
                    const index = this.promotions.findIndex(s => s.id === promotionId);
                    if (index !== -1) {
                        this.promotions[index] = response.promotion;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        async index(){
            const loading = useLoadingStore(); 
            loading.start('promotion');
            try{
                const data = await useApiFetch('/admin/promotions/index');
                if (data && data.promotions) {
                    this.promotions = data.promotions;
                }
            }catch(error){
                alert(error);
            }finally {
                loading.stop('promotion');
            }
        },

        async delete(promotionId: number) {
            const formData = new FormData();
            formData.append('_method', 'DELETE');

            try {
                const response = await useApiFetch(`/admin/promotions/delete/${promotionId}`, {
                    method: 'POST', // Laravel এ DELETE হ্যান্ডেল করার জন্য
                    body: formData,
                });

                if (response && response.success) {
                    this.promotions = this.promotions.filter(s => s.id !== promotionId);
                }
            } catch (error) {
                alert(error);
            }
        },

        find(promotionId:number){
            var promotion = this.promotions.find(promotion => promotion.id === promotionId);

            if(promotion){
                return promotion;
            }
        }
    }
})