
import { defineStore } from "pinia";
import {useLoadingStore} from '~/stores/loading';

export const useTextStore = defineStore('text', {
    state:() => ({
        texts: [] as any,
    }),
    
    actions:{
        async index() {
            const loading = useLoadingStore(); 
            loading.start('text');
            
            try {
                const response = await useApiFetch('/admin/texts');
                if (response && response.texts) {
                this.texts = response.texts;
                return true;
                }
            } catch (error) {
                console.error(error);
                return false;
            } finally {
                loading.stop('text');
            }
        },
        async store(textForm:FormData | any){
            try {
                const response :any = await useApiFetch(`/admin/texts/store`,{
                    method:'POST',
                    body:textForm,
                });

                if (response && response.text) {
                    this.texts.push(response.text);
                    return true;
                }
            }catch(error){
                return false;
            }
        },

        async update(textId: number, form: FormData | any) {
            if (form instanceof FormData) {
                form.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/texts/update/${textId}`, {
                    method: 'POST', 
                    body: form,
                });
    
                if (response && response.text) {
                    const index = this.texts.findIndex(p => p.id === textId);
                    if (index !== -1) {
                        this.texts[index] = response.text;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        findText(text_id:number){
            var text = this.texts.find(text => text.id === text_id);

            if(text){
                return text;
            }
        },
        async delete(textId: number) {
            try {
                const response: any = await useApiFetch(`/admin/texts/delete/${textId}`, {
                    method: 'DELETE',
                });

                if (response && response.success) {
                    // Remove the deleted text from state
                    this.texts = this.texts.filter(text => text.id !== textId);
                    return true;
                }

                return false;
            } catch (error) {
                console.error(error);
                return false;
            }
        }
    }
});