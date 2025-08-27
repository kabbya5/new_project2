<template>
    <!-- Modal BackDrop  -->

    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="$emit('close')">
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-[90%]" @click.stop>
            <div class="bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Add Slider</h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm" class="p-6">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-6 lg:col-span-3">
                        <div class="form-group mb-3">
                            <label for="" class="text-lg"> Slider Name </label>
                            <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                                placeholder="Slider Name" 
                                v-model="form.slider_name"
                                :class="errorStore.validationErrors.slider_name ? 'border-red-500' :'' ">
                            
                            <p v-if="errorStore.validationErrors.slider_name" class="text-red-500">  {{ errorStore.validationErrors.slider_name[0] }} </p>
                        </div>
                    </div>
                </div>
                

                <div class="form-group mb-4">
                    <label for="" class="text-lg"> Bangla Name </label>
                    <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                        placeholder="Bangla Name" 
                        v-model="form.bangla_name"
                          :class="errorStore.validationErrors.bangla_name ? 'border-red-500' :'' ">
                    
                    <p v-if="errorStore.validationErrors.bangla_name" class="text-red-500">  {{ errorStore.validationErrors.bangla_name[0] }} </p>
                </div>

                
                <div class="form-group mb-4">
                    <label for="" class="text-lg"> Hindi Name </label>
                    <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                        placeholder="Hindi Name" 
                        v-model="form.hindi_name"
                          :class="errorStore.validationErrors.hindi_name ? 'border-red-500' :'' ">
                    
                    <p v-if="errorStore.validationErrors.hindi_name" class="text-red-500">  {{ errorStore.validationErrors.hindi_name[0] }} </p>
                </div>

                <div class="form-group mb-4">
                    <label for="" class="text-lg"> Position </label>
                    <input type="number" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                        placeholder="Category Position " required
                        v-model="form.position"
                      :class="errorStore.validationErrors.position ? 'border-red-500' :'' ">
                    
                    <p v-if="errorStore.validationErrors.position" class="text-red-500">  {{ errorStore.validationErrors.position[0] }} </p>
                </div>

                <div class="form-group mb-4">
                    <label for="" class="text-lg"> Catgory Image </label>
                    <div class="flex">
                        <input type="file" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="En Name"
                            @change="handelImageUpload" 
                            :class="errorStore.validationErrors.image ? 'border-red-500' :'' ">

                        <img v-if="imagePreview" :src="imagePreview" alt="" class="ml-2 mt-2 w-[40px] h-[40px]">
                    </div>
                    
                    <p v-if="errorStore.validationErrors.image" class="text-red-500">  {{ errorStore.validationErrors.image[0] }} </p>
                    
                </div>

                <div class="flex justify-end">
                     <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import type { SliderForm } from '~/types/SliderForm';
    import { useCategoryStore } from '~/stores/category';
    import { useErrorStore } from '~/stores/error';

    const categoryStore = useCategoryStore();
    const errorStore = useErrorStore();
    
    const emit = defineEmits(['close']);

    const form = ref<SliderForm> ({
        english_name:'',
        bangla_name:'',
        hindi_name:'',
        image:null,
        position: null,
    });

    const imagePreview = ref<string |null> (null);

    const handelImageUpload = (event:Event) =>{
        const file = (event.target as HTMLInputElement).files?.[0]
        if(file){
            form.value.image = file;
            imagePreview.value = URL.createObjectURL(file);
        }
    }

    const submitForm = async() => {
        try{
            const formData = new FormData();
            formData.append('english_name', form.value.english_name);
            formData.append('bangla_name', form.value.bangla_name);
            formData.append('hindi_name',form.value.hindi_name);
            
            formData.append('position', String(form.value.position ?? ''));
            
            if(form.value.image){
                formData.append('image', form.value.image);
            }
            await categoryStore.storeCategory(formData);
            emit('close');
        }catch(error){
            alert(error);
        }
    }

</script>
