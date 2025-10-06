
import { defineStore } from "pinia";
import {useLoadingStore} from '~/stores/loading';

export const useCurrencyStore = defineStore('currency', {
    state:() => ({
        currencies: [] as any,
    }),

    actions:{
        async index() {
            const loading = useLoadingStore(); 
            loading.start('currency');
            
            try {
                const response = await useApiFetch('/admin/currencies');
                if (response && response.currencies) {
                this.currencies = response.currencies;
                return true;
                }
            } catch (error) {
                console.error(error);
                return false;
            } finally {
                loading.stop('currency');
            }
        },
        async store(currencyForm:FormData | any){
            try {
                const response :any = await useApiFetch(`/admin/currencies/store`,{
                    method:'POST',
                    body:currencyForm,
                });

                if (response && response.currency) {
                    this.currencies.push(response.currency);
                    return true;
                }
            }catch(error){
                return false;
            }
        },

        async update(currencyId: number, form: FormData | any) {
            if (form instanceof FormData) {
                form.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/currencies/update/${currencyId}`, {
                    method: 'POST', 
                    body: form,
                });
    
                if (response && response.currency) {
                    const index = this.currencies.findIndex(p => p.id === currencyId);
                    if (index !== -1) {
                        this.currencies[index] = response.currency;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        findCurrency(currency_id:number){
            var currency = this.currencies.find(currency => currency.id === currency_id);

            if(currency){
                return currency;
            }
        }
    }
});