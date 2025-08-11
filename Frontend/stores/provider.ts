import {defineStore} from 'pinia';
import type { Provider } from '~/types/provider';
import type { ProviderForm } from '~/types/providerForm';
import { useLoadingStore } from '~/stores/loading';

export const useProviderStore = defineStore('provider',{
    state:()=>({
        providers:[] as Provider[],
    }),

    actions:{
        async storeProvider(ProviderForm:FormData | ProviderForm){
            try {
                const response = await useApiFetch(`/admin/providers/store`,{
                    method:'POST',
                    body:ProviderForm,
                });

                if (response && response.provider) {
                    this.providers.push(response.provider);
                    return true;
                }
            }catch(error){
                return false;
            }
        },

        async fetchProviders(){
            const loading = useLoadingStore(); 
            loading.start('provider');
            try{
                const data = await useApiFetch('/game/providers/index');
                if (data && data.providers) {
                    this.providers = data.providers;
                }
            }catch(error){
                alert(error);
            }finally {
                loading.stop('provider');
            }  
        },

        getGameByProvider(slug: string) {
        const provider = this.providers.find(provider => provider.slug === slug);
            if (provider && provider.games) {
                return Promise.resolve(provider.games);
            }
            return Promise.resolve([]); 
        }
    }
})