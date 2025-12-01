<template>
    <AdminSliderForm v-if="isModalOpen" :sliderId="currentId" @close="isModalOpen=false"/>

    <div class="bg p-3">
        <div class="flex justify-between items-center">
            <div class="form-group">
                <input type="text" placeholder="Search slider" class="border border-gray-200 px-3 py-1 dark:border-gray-200">
            </div>

            <div class="flex items-center">
                <button @click="createUpdateModal()" class="primary-button px-3 py-1 bg-sky-500 text-white rounded-md"> Add Slider </button>
            </div>
        </div>

        <div class="my-5">
            <LoadingSpinner v-if="loading.isLoading('slider')" />
            <div class="overflow-x-auto" v-else>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-card">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Slider Name
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Status
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Desktop Image
                        </th>

                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">
                            Mobile Image
                        </th>

                       
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="slider in sliderStore.sliders" :key="slider.id">
                            <td class="px-6 py-4 whitespace-nowrap text-sm">{{ slider.slider_name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm capitalize">{{ slider.status }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm"> 
                                <img :src="slider.desktop_image" :alt="slider.slider_name" class="w-10">
                            </td>
                        
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                <img :src="slider.mobile_image" :alt="slider.slider_name" class="w-10">
                            </td>

                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button @click="createUpdateModal(slider.id)" class="hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-edit"></i>
                                </button>

                                <button @click="deleteSlider(slider.id)" class="ml-2 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { useSliderStore } from '~/stores/sliderStore';
    import { useLoadingStore } from '~/stores/loading';

    const sliderStore = useSliderStore();
    const loading = useLoadingStore();

    definePageMeta({
        middleware:['auth', 'admin'],
        layout:'admin',
    })

    const isModalOpen = ref<boolean>(false);
    const currentId = ref<number | null>(null);

    const createUpdateModal = (id:number|null = null) =>{
        currentId.value = id;
        isModalOpen.value = true;
    }

    onMounted (async () =>{
        await sliderStore.fetchSliders();
    })

    const deleteSlider = async(id:number) => {
        const confirmed = confirm("Are you sure you want to delete this slider?");
        if (!confirmed) {
            return; 
        }
        await sliderStore.deleteSlider(id);
    }

</script>