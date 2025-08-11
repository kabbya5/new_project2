import {defineStore} from 'pinia';

export const useLoadingStore = defineStore('loading', {
    state:() =>({
        loading: {'top-seller':false} as Record<string, boolean>,
    }),

    actions:{
        start(section:string){
            this.loading[section] = true
        },
        stop(section:string){
            this.loading[section] = false 
        }
    },
    getters:{
        isLoading:(state) =>(section:string) =>{
            return !!state.loading[section];
        }
    }
});