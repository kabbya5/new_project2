import { defineStore } from "pinia";
import {useLoadingStore} from '~/stores/loading';
import type { Label } from "~/types/label";

export const useLabelStore = defineStore('label',{
    state:()=>({
        labels:[] as Label[],
    }),
    
    actions:{
        async index(){
            const loading = useLoadingStore();
            loading.start('label');

            try{
                const response :any = await useApiFetch('/labels/index');
                if(response.data){
                    this.labels = response.data;
                }
            }catch(error){
                alert(error);
            }finally{
                loading.stop('label');
            }
        },

        async store(labelForm:FormData|Label){
            try{
                const response :any = await useApiFetch('/admin/labels/store',{
                    method:'POST',
                    body:labelForm,
                });

                if (response && response.data) {
                    this.labels.push(response.data);
                }
            }catch(error){
                console.log(error);
            }
        },

        async update(labelId:number, labelForm:FormData|Label){
            if (labelForm instanceof FormData) {
                labelForm.append('_method', 'PUT');
            }
            try {
                const response:any = await useApiFetch(`/admin/labels/update/${labelId}`, {
                    method: 'POST', 
                    body: labelForm,
                });
    
                if (response && response.data) {
                    const index = this.labels.findIndex(p => p.id === labelId);
                    if (index !== -1) {
                        this.labels[index] = response.data;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        findLabel(label_id: number): Label | undefined {
            return this.labels.find(label => label.id === label_id);
        }, 
    },
});