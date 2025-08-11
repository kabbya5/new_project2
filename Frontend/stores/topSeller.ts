import {defineStore} from 'pinia';
import { useLoadingStore } from '~/stores/loading';
import type { topSeller } from '~/types/topSeller';

export const useTopSellerStore = defineStore('topSeller',{
    state:() => ({
        topSellers: [] as topSeller[],
        currentPage:1,
        totalPages: 2,
        perPage:10,
    }),

    actions:{
        async fetchTopsellers(page:number = 1){
            const loading = await useLoadingStore()
            loading.start('top-seller');
            try{
                const {data, error} = await  useFetch('/api/top-sellers');
                if (error.value) {
                    alert(error.value);
                }
                console.log('Raw data ref:', data.value);
                if(data.value){
                    this.topSellers = toRaw(data.value);
                }
                console.log(this.topSellers);
            } catch (err: any) {
                alert('Unknown error');
            } finally {
                loading.stop('top-seller');
            }
        }
    }
})