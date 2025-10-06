<template>
    <!-- Modal BackDrop  -->

    <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click="$emit('close')">
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-md" @click.stop>
            <div class="bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Add Wallet</h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm">
                <div class="p-5">
                    <div class="form-group mb-3">
                        <label for="" class="text-lg"> Provider </label>
                        <select type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="En Name" 
                            v-model="form.provider"
                            :class="errorStore.validationErrors.provider ? 'border-red-500' :'' "
                        >
                            <option value=""> Select </option>

                        </select>
                        
                        <p v-if="errorStore.validationErrors.provider" class="text-red-500">  {{ errorStore.validationErrors.english_name[0] }} </p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import type { CategoryForm } from '~/types/ctegoryForm';
    import { useCategoryStore } from '~/stores/category';
    import { useErrorStore } from '~/stores/error';

    const categoryStore = useCategoryStore();
    const errorStore = useErrorStore();
    
    const emit = defineEmits(['close']);

    const providerOptions = ref([
        
    ])

    const form = ref<CategoryForm> ({
        english_name:'',
        bangla_name:'',
        hindi_name:'',
        image:null,
        position: null,
    });

    const props = defineProps<{ categoryId: number | null }>();
    const imagePreview = ref<string | null>(null);

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
            
            if (props.categoryId) {
                await categoryStore.updateCategory(props.categoryId, formData);
            } else {
                await categoryStore.storeCategory(formData);
            }
            if(!errorStore.message){
                emit('close');
            }
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.categoryId) {
            const category = categoryStore.findCategory(props.categoryId);
            if(category){
                form.value.english_name = category.english_name ?? null;
                form.value.bangla_name = category.bangla_name ?? null;
                form.value.hindi_name = category.hindi_name ?? null;
                form.value.position = category.position ?? null;
                imagePreview.value = category.image_url ?? null;
            }
        }
    });

</script>
