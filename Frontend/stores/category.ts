import {defineStore} from 'pinia';
import type { Category } from '~/types/category';
import type { CategoryForm } from '~/types/ctegoryForm';
import { useLoadingStore } from '~/stores/loading';

export const useCategoryStore = defineStore('category',{
    state:()=>({
        categories:[] as Category[],
    }),

    actions:{
        async storeCategory(categoryForm:FormData | CategoryForm){
            try {
                const response = await useApiFetch(`/admin/categories/store`,{
                    method:'POST',
                    body:categoryForm,
                });

                if (response && response.category) {
                    this.categories.push(response.category);
                }
            }catch(error){
                alert(error);
            }
        },

        async fetchCategories(){
            const loading = useLoadingStore(); 
            loading.start('category');
            try{
                const data = await useApiFetch('/game/categories/index');
                if (data && data.categories) {
                    this.categories = data.categories;
                }
            }catch(error){
                alert(error);
            }finally {
                loading.stop('category');
            }
        },

        getGameByCategory(slug:string){
            const category = this.categories.find(category => category.slug === slug);
            if(category && category.games) {
                return Promise.resolve(category.games);
            }
            return Promise.resolve([]); 
        }
    }
})