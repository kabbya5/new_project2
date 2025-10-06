<template>
  <!-- Modal Backdrop -->
  <div
    class="fixed inset-0 bg-black/50 flex items-top justify-center z-50"
    @click="$emit('close')"
  >
    <div
      class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-lg mx-4 animate-fadeIn"
      @click.stop
    >
      <!-- Modal Header -->
      <div
        class="modal-header bg-gray-300 dark:bg-gray-900 py-3 px-6 flex items-center justify-between rounded-t-lg"
      >
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
          Moving Code
        </h2>
        <button
          @click="$emit('close')"
          class="bg-red-500 hover:bg-red-600 w-[30px] h-[30px] flex items-center justify-center rounded-full text-white transition"
        >
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="submitForm" class="p-6 space-y-6">
        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            >Moving Text</label
          >
          <input
            id="text"
            type="text"
            class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter Moving Text"
            v-model="form.text"
            required
            :class="errorStore.validationErrors.text ? 'border-red-500' : ''"
          />
          <p
            v-if="errorStore.validationErrors.text"
            class="text-red-500 text-sm mt-1"
          >
            {{ errorStore.validationErrors.text[0] }}
          </p>
        </div>

        <div class="flex justify-end mt-3">
          <button
            type="submit"
            class="bg-blue-500 hover:bg-blue-600 text-white px-5 py-2 rounded transition"
          >
            Save
          </button>
        </div>
      </form>
    </div>
  </div>
</template>


<script setup lang="ts">
    import { useTextStore } from '~/stores/text';
    import { useErrorStore } from '~/stores/error';

    const textStore = useTextStore();
    const errorStore = useErrorStore();
    
    const props = defineProps<{ text_id: number | null }>();
    const emit = defineEmits(['close']);

    const form = ref<any> ({
        text:'',
    });

    const submitForm = async() => {
        try{
            const formData = new FormData();
            formData.append('text', form.value.text);
          
           if (props.text_id) {
                await textStore.update(props.text_id, formData);
            } else {
                await textStore.store(formData);
            }
            if(!errorStore.message){
                emit('close');
            }
            
        }catch(error){
            alert(error);
        }
    }

    onMounted(() => {
        if (props.text_id) {
            const text = textStore.findText(props.text_id);
            if(text){
                form.value.text = text.text ?? null;
            }
        }
    });

</script>

