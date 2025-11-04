<template>
    <!-- Modal BackDrop  -->

    <div class="fixed w-full top-8 left-0 bg-black/50 flex items-center justify-center z-50" @click="$emit('close')">
        <div class="bg-white dark:bg-gray-800 rounded w-full max-w-md" @click.stop>
            <div class="modal-header bg-gray-300 dark:bg-gray-900 py-2 px-6 flex items-center justify-between">
                <h2 class="text-xl font-bold">Add Provider</h2>
                <button @click="$emit('close')" class="bg-red-500 w-[30px] h-[30px] rounded-full text-white"> <i class="fa-solid fa-xmark"></i></button>
            </div>
            
            <form @submit.prevent="submitForm">
                <div class="grid grid-cols-12 gap-4 p-6">    
                    <div class="col-span-12 form-group">
                        <label for="" class="text-lg"> Provider Id </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Provider Id" 
                            v-model="form.provider_id"
                            :class="errorStore.validationErrors.provider_id ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.provider_id" class="text-red-500">  {{ errorStore.validationErrors.provider_id[0] }} </p>
                    </div>
                
                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> English Name </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="En Name" 
                            v-model="form.english_name"
                            :class="errorStore.validationErrors.english_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.english_name" class="text-red-500">  {{ errorStore.validationErrors.english_name[0] }} </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Bangla Name </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Bangla Name" 
                            v-model="form.bangla_name"
                            :class="errorStore.validationErrors.bangla_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.bangla_name" class="text-red-500">  {{ errorStore.validationErrors.bangla_name[0] }} </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Hindi Name </label>
                        <input type="text" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Hindi Name" 
                            v-model="form.hindi_name"
                            :class="errorStore.validationErrors.hindi_name ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.hindi_name" class="text-red-500">  {{ errorStore.validationErrors.hindi_name[0] }} </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Select Category </label>

                        <Multiselect class="border dark:bg-gray-700 dark:text-white focus:outline-none"
                            v-model="form.categories"
                            :options="options"
                            :multiple="true"
                            :close-on-select="false"
                            placeholder="Select Category"
                            label="english_name"
                            track-by="id"
                        />

                        <p v-if="errorStore.validationErrors.hindi_name" class="text-red-500">
                            {{ errorStore.validationErrors.hindi_name[0] }}
                        </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Position </label>
                        <input type="number" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                            placeholder="Category Position " required
                            v-model="form.position"
                        :class="errorStore.validationErrors.position ? 'border-red-500' :'' ">
                        
                        <p v-if="errorStore.validationErrors.position" class="text-red-500">  {{ errorStore.validationErrors.position[0] }} </p>
                    </div>

                    <div class="form-group col-span-12">
                        <label for="" class="text-lg"> Provider Image </label>
                        <div class="flex">
                            <input type="file" class="w-full border mt-2 px-2 py-1 dark:bg-gray-700 dark:text-white focus:outline-none" 
                                placeholder="Enter Logo Path"
                                @change="handelImageUpload" 
                                :class="errorStore.validationErrors.image ? 'border-red-500' :'' ">

                            <img v-if="imagePreview" :src="imagePreview" alt="" class="ml-2 mt-2 w-[40px] h-[40px]">
                        </div>
                        
                        <p v-if="errorStore.validationErrors.image" class="text-red-500">  {{ errorStore.validationErrors.image[0] }} </p>
                        
                    </div>

                    <div class="flex justify-en3 col-span-12">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
    import type { ProviderForm } from '~/types/providerForm';
    import { useCategoryStore } from '~/stores/category';
    import { useProviderStore } from '~/stores/provider';
    import { useErrorStore } from '~/stores/error';

    const categoryStore = useCategoryStore();
    const providerStore = useProviderStore();
    const errorStore = useErrorStore();
    
    const emit = defineEmits(['close']);

    const form = ref<ProviderForm> ({
        provider_id: null,
        english_name:'',
        bangla_name:'',
        hindi_name:'',
        image:null,
        position:null,
        categories: [],
    });

    const options = ref<any[]>([]);
    const imagePreview = ref<string | null>(null);

    const handelImageUpload = (event:Event) =>{
        const file = (event.target as HTMLInputElement).files?.[0]
        if(file){
            form.value.image = file;
            imagePreview.value = URL.createObjectURL(file);
        }
    }

    watch(
        () => categoryStore.categories,
        (newCategories) => {
            options.value = newCategories.map((category: any) => ({
                id: category.id,
                english_name: category.english_name,
            }))
        },
        { immediate: true }
    )

    const props = defineProps<{ providerId: number | null }>();

    const submitForm = async() => {
        try{
            const formData = new FormData();
            formData.append('provider_id', form.value.provider_id ? String(form.value.provider_id) : '');
            formData.append('english_name', form.value.english_name);
            formData.append('bangla_name', form.value.bangla_name);
            formData.append('hindi_name',form.value.hindi_name);
            formData.append('position', String(form.value.position ?? ''));

            form.value.categories.forEach((category, index) => {
                formData.append(`categories[${index}]`, category.id);
            });

            if(form.value.image){
                formData.append('image', form.value.image);
            }
            
            if (props.providerId) {
                await providerStore.updateProvider(props.providerId, formData);
            } else {
                await providerStore.storeProvider(formData);
            }
            if(!errorStore.message){
                emit('close');
            }
            
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.providerId) {
            const provider = providerStore.findProvider(props.providerId);
            if(provider){
                form.value.english_name = provider.english_name ?? null;
                form.value.bangla_name = provider.bangla_name ?? null;
                form.value.hindi_name = provider.hindi_name ?? null;
                form.value.position = provider.position ?? null;
                imagePreview.value = provider.logo ?? null;
                form.value.categories = provider.categories ?? [];
            }
        }
    });

</script>

<style scoped>
.multiselect-custom .multiselect {
  background-color: white;
  border: 1px solid #d1d5db; /* Tailwind's border-gray-300 */
  color: black;
  border-radius: 0.375rem; /* rounded-md */

  /* Dark mode override */
  @media (prefers-color-scheme: dark) {
    background-color: #1f2937; /* dark:bg-gray-800 */
    border-color: #4b5563; /* dark:border-gray-600 */
    color: white; /* dark:text-white */
  }
}

.multiselect-custom .multiselect__input {
  background-color: transparent;
  color: black;

  @media (prefers-color-scheme: dark) {
    color: #1f2937;
  }
}

.multiselect-custom .multiselect__option--highlight {
  background-color: #6366f1; /* bg-indigo-500 */
  color: white;
}

.multiselect-custom .multiselect__tag {
  background-color: #4f46e5; /* bg-indigo-600 */
}
</style>
