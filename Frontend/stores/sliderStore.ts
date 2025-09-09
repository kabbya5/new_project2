import {defineStore} from 'pinia';
import type { Slider } from '~/types/slider';
import type { SliderForm } from '~/types/sliderForm';

import { useLoadingStore } from '~/stores/loading';

export const useSliderStore = defineStore('slider',{
    state:()=>({
        sliders:[] as Slider[],
    }),

    actions:{
        async storeSlider(sliderFormForm:FormData | SliderForm){
            try {
                const response = await useApiFetch(`/admin/sliders/store`,{
                    method:'POST',
                    body:sliderFormForm,
                });

                if (response && response.slider) {
                    this.sliders.push(response.slider);
                }
            }catch(error){
                alert(error);
            }
        },

        async updateSlider(sliderId: number, sliderForm: FormData | SliderForm) {
            if (sliderForm instanceof FormData) {
                sliderForm.append('_method', 'PUT');
            }
            try {
                const response = await useApiFetch(`/admin/sliders/update/${sliderId}`, {
                    method: 'POST', // অথবা 'PUT' যদি তোমার API তে PUT লাগে
                    body: sliderForm,
                });

                if (response && response.slider) {
                    // store এর ভিতরে পুরোনো slider replace করা
                    const index = this.sliders.findIndex(s => s.id === sliderId);
                    if (index !== -1) {
                        this.sliders[index] = response.slider;
                    }
                }
            } catch (error) {
                alert(error);
            }
        },

        async fetchSliders(){
            const loading = useLoadingStore(); 
            loading.start('slider');
            try{
                const data = await useApiFetch('/sliders/index');
                if (data && data.sliders) {
                    this.sliders = data.sliders;
                }
            }catch(error){
                alert(error);
            }finally {
                loading.stop('slider');
            }
        },

        async deleteSlider(sliderId: number) {
            const formData = new FormData();
            formData.append('_method', 'DELETE');

            try {
                const response = await useApiFetch(`/admin/sliders/delete/${sliderId}`, {
                    method: 'POST', // Laravel এ DELETE হ্যান্ডেল করার জন্য
                    body: formData,
                });

                if (response && response.success) {
                    this.sliders = this.sliders.filter(s => s.id !== sliderId);
                }
            } catch (error) {
                alert(error);
            }
        },

        findSlider(sliderId:number){
            var slider = this.sliders.find(slider => slider.id === sliderId);

            if(slider){
                return slider;
            }
        }
    }
})