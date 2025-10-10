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

        async updateProvider(providerId: number, providerForm: FormData | ProviderForm) {
            if (providerForm instanceof FormData) {
                providerForm.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/providers/update/${providerId}`, {
                    method: 'POST', // অথবা 'PUT' যদি তোমার API তে PUT লাগে
                    body: providerForm,
                });

                if (response && response.provider) {
                    // store এর ভিতরে পুরোনো provider replace করা
                    const index = this.providers.findIndex(p => p.id === providerId);
                    if (index !== -1) {
                        this.providers[index] = response.provider;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        async getGameByProvider(slug: string) {
            const loading = useLoadingStore();
            loading.start('games');

            try {
                const provider = this.providers.find(p => p.slug === slug);

                if (provider && provider.games) {
                    return provider.games;
                }

                return [];
            } finally {
                loading.stop('games'); // সবসময় বন্ধ হবে
            }
        },

        findProvider(provider_id:number){
            var provider = this.providers.find(prov => prov.id === provider_id);

            if(provider){
                return provider;
            }
        },

        async findProviderByCategory(slug:string){
            const loading = useLoadingStore(); 
            loading.start('provider');
            
            if(slug == 'sports'){
                 try{
                    const data = await useApiFetch('/game/sports-play/');

                    if (data.status === 1 && data.launch_url) {
                        window.open(data.launch_url, '_blank');
                    }

                }catch(error){
                    alert(error);
                }finally {
                    loading.stop('provider');
                } 
            }else{

              try{
                  const data = await useApiFetch('/game/category/providers/' + slug);

                  if (data.status === 1 && response.game_url) {
                      window.open(response.game_url, '_blank');
                  }

                  if (data && data.providers) {
                      return data.providers;
                  }
              }catch(error){
                  alert(error);
              }finally {
                  loading.stop('provider');
              } 

              return [];
            }
        }
    }
})