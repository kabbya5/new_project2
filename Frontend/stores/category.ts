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

        async updateCategory(categoryId: number, categoryForm: FormData | CategoryForm) {
            if (categoryForm instanceof FormData) {
                categoryForm.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/categories/update/${categoryId}`, {
                    method: 'POST', // অথবা 'PUT' যদি তোমার API তে PUT লাগে
                    body: categoryForm,
                });

                if (response && response.category) {
                    // store এর ভিতরে পুরোনো category replace করা
                    const index = this.categories.findIndex(c => c.id === categoryId);
                    if (index !== -1) {
                        this.categories[index] = response.category;
                    }
                }
            } catch (error) {
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
        },

        findCategory(category_id:number){
            var category = this.categories.find(cat => cat.id === category_id);

            if(category){
                return category;
            }
        }
    }
})