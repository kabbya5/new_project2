<template>
    <!-- Modal BackDrop  -->

    <div class="fixed top-0 w-full left-0 bg-black/50 flex items-center justify-center z-50 h-full" >
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-[90%] max-h-[90%]" @click.stop style="overflow: auto;">
            <div class="bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Promotion & Affilate </h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm" class="h-100%">
                <div class="grid grid-cols-12 gap-4 px-6 py-3">
                    <div class="col-span-12 md:col-span-6 lg:col-span-4">
                        <div class="form-group mb-3">
                            <label for="" class="text-lg"> Title </label>
                            <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                                placeholder=" Title" 
                                v-model="form.title" required
                                :class="errorStore.validationErrors.title ? 'border-red-500' :'' ">
                            
                            <p v-if="errorStore.validationErrors.title" class="text-red-500">  {{ errorStore.validationErrors.title[0] }} </p>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6 lg:col-span-4 flex">
                        <div class="form-group mb-3">
                            <label class="text-lg"> Image </label>
                            <input  :required="!promotionId"
                            type="file" 
                            accept="image/*"
                            class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none"
                            :class="errorStore.validationErrors.baner_image ? 'border-red-500' : '' "
                            @change="onDesktopImageChange"
                            />
                          
                            <p v-if="errorStore.validationErrors.baner_image" class="text-red-500">
                            {{ errorStore.validationErrors.baner_image[0] }}
                            </p>
                        </div>

                        <div v-if="imagePreview" class="mt-8 ml-3">
                            <img :src="imagePreview" alt="Desktop Preview" class="w-[80px] h-[40px] object-cover rounded" />
                        </div>

                    </div>

                    <div class="col-span-12 md:col-span-6 lg:col-span-2">
                        <div class="form-group mb-3">
                            <label for="" class="text-lg"> Type </label>
                            <select
                            class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none"
                            v-model="form.type" required
                            :class="errorStore.validationErrors.type ? 'border-red-500' : '' "
                            >
                            <option value="promotion"> Promotion </option>
                            <option value="affiliate"> Affiliate </option>
                            </select>

                            <p v-if="errorStore.validationErrors.type" class="text-red-500">
                            {{ errorStore.validationErrors.type[0] }}
                            </p>
                        </div>
                    </div>

                    <div class="col-span-12 md:col-span-6 lg:col-span-2">
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


                    <div class="col-span-12">
                        <label class="text-lg mb-2 block"> Promotional Content </label>
                        <ClientOnly >
                            <div class="admin-editor-wrapper">
                                <AdminTextEditor v-model:content="form.content" />
                                <p v-if="errorStore.validationErrors.content" class="text-red-500">
                                    {{ errorStore.validationErrors.content[0] }}
                                </p>
                            </div>
                        </ClientOnly>
                        
                        
                        <div class="my-4 px-2 border rounded bg-gray-50 dark:bg-gray-900">
                            <h3 class="font-bold mb-2">Preview:</h3>
                            <div v-html="form.content" class="w-full h-full"></div>
                        </div>
                    </div>

                    <div class="col-span-12 flex justify-end mb-3">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">

    import type { PromotionForm } from '~/types/promotionForm';
    import { usePromotionStore } from '~/stores/promotion';
    import { useErrorStore } from '~/stores/error';

    const promotionStore = usePromotionStore();
    const errorStore = useErrorStore();
    
    const emit = defineEmits(['close']);

    const props = defineProps<{ promotionId: number | null }>();
    const imagePreview = ref<null | string >(null)

    const form = ref<PromotionForm> ({
        id:null,
        title:'',
        status:'active',
        baner_image: null,
        content:'',
        type:'promotion',
    });

    onMounted(() => {
        if (props.promotionId) {
            const promotion = promotionStore.find(props.promotionId);
            if(promotion){
                form.value.title = promotion.title ?? null;
                form.value.status = promotion.status ?? null;
                form.value.content = promotion.content ?? null;
                imagePreview.value = promotion.image_url ?? null;
            }
        }
    });

    const submitForm = async() => {
        try{

            const formData = new FormData();
            formData.append('title', form.value.title);
            formData.append('status', form.value.status);
            formData.append('type', form.value.type);

            if(form.value.baner_image){
                formData.append('baner_image', form.value.baner_image);
            }

            formData.append('content', form.value.content);

            if (props.promotionId) {
                await promotionStore.update(props.promotionId, formData);
            } else {
                await promotionStore.store(formData);
            }

            if(!errorStore.message){
                emit('close');
            }

        }catch(error){
            alert(error);
        }
    }

    const onDesktopImageChange = (event:Event) => {
        const file = (event.target as HTMLInputElement).files?.[0];
        
        if(file){
            form.value.baner_image = file;
            imagePreview.value = URL.createObjectURL(file);
        }
    }

</script>