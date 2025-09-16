<template>
    <!-- Modal BackDrop  -->

    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" >
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-[90%] max-h-[90%]" @click.stop style="overflow: auto;">
            <div class="bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Add Slider</h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm" class="px-6 py-3 h-100%">
                <div class="grid grid-cols-12 gap-4">
                    <div class="col-span-12 md:col-span-6 lg:col-span-3">
                        <div class="form-group mb-3">
                            <label for="" class="text-lg"> Slider Name </label>
                            <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                                placeholder="Slider Name" 
                                v-model="form.slider_name" required
                                :class="errorStore.validationErrors.slider_name ? 'border-red-500' :'' ">
                            
                            <p v-if="errorStore.validationErrors.slider_name" class="text-red-500">  {{ errorStore.validationErrors.slider_name[0] }} </p>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6 lg:col-span-3">
                        <div class="form-group mb-3">
                            <label for="" class="text-lg"> Status </label>
                            <select
                            class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none"
                            v-model="form.status" required
                            :class="errorStore.validationErrors.status ? 'border-red-500' : '' "
                            >
                            <option value="active"> Active </option>
                            <option value="inactive"> Inactive </option>
                            </select>

                            <p v-if="errorStore.validationErrors.status" class="text-red-500">
                            {{ errorStore.validationErrors.status[0] }}
                            </p>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6 lg:col-span-3">
                        <div class="form-group mb-3">
                            <label class="text-lg"> Desktop Image </label>
                            <input  :required="!sliderId"
                            type="file" 
                            accept="image/*"
                            class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none"
                            :class="errorStore.validationErrors.desktop_image ? 'border-red-500' : '' "
                            @change="onDesktopImageChange"
                            />
                            <div v-if="desktopPreview" class="mt-2">
                            <img :src="desktopPreview" alt="Desktop Preview" class="w-full h-32 object-cover rounded" />
                            </div>
                            <p v-if="errorStore.validationErrors.desktop_image" class="text-red-500">
                            {{ errorStore.validationErrors.desktop_image[0] }}
                            </p>
                        </div>
                    </div>

                        <!-- ✅ Mobile Image -->
                    <div class="col-span-12 md:col-span-6 lg:col-span-3">
                        <div class="form-group mb-3">
                            <label class="text-lg"> Mobile Image </label>
                            <input  :required="!sliderId"
                            type="file" 
                            accept="image/*"
                            class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none"
                            :class="errorStore.validationErrors.mobile_image ? 'border-red-500' : '' "
                            @change="onMobileImageChange"
                            />
                            <div v-if="mobilePreview" class="mt-2">
                            <img :src="mobilePreview" alt="Mobile Preview" class="w-full h-32 object-cover rounded" />
                            </div>
                            <p v-if="errorStore.validationErrors.mobile_image" class="text-red-500">
                            {{ errorStore.validationErrors.mobile_image[0] }}
                            </p>
                        </div>
                    </div>

                    <div class="col-span-12">
                        <label class="text-lg mb-2 block">Slider Content</label>
                        <ClientOnly >
                            <div class="admin-editor-wrapper">
                                <AdminTextEditor v-model:content="form.slider_content" />
                                <p v-if="errorStore.validationErrors.slider_content" class="text-red-500">
                                    {{ errorStore.validationErrors.slider_content[0] }}
                                </p>
                            </div>
                        </ClientOnly>
                        
                        
                        <div class="my-4 px-2 border rounded bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-bold mb-2">Preview:</h3>
                            <div v-html="form.slider_content" class="w-full h-full"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex justify-end">
                     <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">

    import type { SliderForm } from '~/types/sliderForm';
    import { useSliderStore } from '~/stores/sliderStore';
    import { useErrorStore } from '~/stores/error';

    const sliderStore = useSliderStore();
    const errorStore = useErrorStore();
    
    const emit = defineEmits(['close']);

    const props = defineProps<{ sliderId: number | null }>();

    const form = ref<SliderForm> ({
        slider_name:'',
        status:'active',
        desktop_image: null,
        mobile_image: null,
        slider_content:'',
    });

    onMounted(() => {
        if (props.sliderId) {
            const slider = sliderStore.findSlider(props.sliderId);
            if(slider){
                form.value.slider_name = slider.slider_name ?? null;
                form.value.status = slider.status ?? null;
                form.value.slider_content = slider.slider_content ?? null;

                // যদি previous images থাকে, preview হিসেবে দেখাও
                desktopPreview.value = slider.desktop_image ?? null;
                mobilePreview.value = slider.mobile_image ?? null;
            }
        }
    });

    const submitForm = async() => {
        try{
            const formData = new FormData();
            formData.append('slider_name', form.value.slider_name);
            formData.append('status', form.value.status);
            if(form.value.desktop_image){
                formData.append('desktop_image', form.value.desktop_image);
            }

            if(form.value.mobile_image){
                formData.append('mobile_image',form.value.mobile_image);
            }
            
            formData.append('slider_content', form.value.slider_content);

            if (props.sliderId) {
                await sliderStore.updateSlider(props.sliderId, formData);
            } else {
                await sliderStore.storeSlider(formData);
            }

            if(!errorStore.message){
                emit('close');
            }

        }catch(error){
            alert(error);
        }
    }

    const desktopPreview = ref<string | null>(null)
    const mobilePreview = ref<string | null>(null)

    const onDesktopImageChange = (event:Event) => {
        const file = (event.target as HTMLInputElement).files?.[0];
        
        if(file){
            form.value.desktop_image = file;
            desktopPreview.value = URL.createObjectURL(file);
        }
    }

    const onMobileImageChange = (event:Event) => {
        const file = (event.target as HTMLInputElement).files?.[0];
        if(file){
            form.value.mobile_image = file
            mobilePreview.value = URL.createObjectURL(file);
        }
    }

</script>