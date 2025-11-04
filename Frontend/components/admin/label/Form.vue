<template>
  <!-- Modal Backdrop -->
  <div
    class="fixed w-full top-8 left-0 bg-black/50 flex items-top justify-center z-50"
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
          VIP Level
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
            >Level Position</label
          >
          <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter Postion 1,2,3"
            v-model="form.position"
            required
            :class="errorStore.validationErrors.position ? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.position" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.position[0] }}
          </p>
        </div>

        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            >Level Name </label
          >
          <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter name"
            v-model="form.name" required
            :class="errorStore.validationErrors.name ? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.position" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.name[0] }}
          </p>
        </div>

        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            > Min Deposit </label
          >
          <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter min bet"
            v-model="form.min_bet" required
            :class="errorStore.validationErrors.min_bet ? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.min_bet" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.min_bet[0] }}
          </p>
        </div>

        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            > Max Depoosit </label
          >
          <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter max bet"
            v-model="form.max_bet"
            required
            :class="errorStore.validationErrors.max_bet ? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.max_bet" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.max_bet[0] }}
          </p>
        </div>

        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            > Deposit Bonus % </label
          >
          <input id="number" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter daily bonus"
            v-model="form.deposit_bonus" required
            :class="errorStore.validationErrors.deposit_bonus? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.deposit_bonus" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.deposit_bonus[0] }}
          </p>
        </div>

        <div class="form-group">
          <label for="text" class="text-lg font-semibold dark:text-white"
            > Daily Bonus </label
          >
          <input id="text" type="text" class="w-full border mt-2 px-3 py-2 rounded dark:bg-gray-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter daily bonus"
            v-model="form.daily_bonus" required
            :class="errorStore.validationErrors.daily_bonus? 'border-red-500' : ''"
          />

          <p v-if="errorStore.validationErrors.daily_bonus" class="text-red-500 text-sm mt-1">
            {{ errorStore.validationErrors.daily_bonus[0] }}
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
    import type { Label } from '~/types/label';
    import { useLabelStore } from '~/stores/label';
    import { useErrorStore } from '~/stores/error';

    const labelStore = useLabelStore();
    const errorStore = useErrorStore();
    
    const props = defineProps<{ text_id: number | null }>();
    const emit = defineEmits(['close']);

    const form = ref<Label>({
        id: null,
        position: null,   
        name: null,
        min_bet: null,
        max_bet: null,
        daily_bonus: null,
        deposit_bonus:null,
    });

    const submitForm = async() => {
        try{
            const formData = new FormData();
            if (form.value.name !== null) formData.append('name', form.value.name);
            if (form.value.position !== null) formData.append('position', String(form.value.position));
            if (form.value.min_bet !== null) formData.append('min_bet', String(form.value.min_bet));
            if (form.value.max_bet !== null) formData.append('max_bet', String(form.value.max_bet));
            if (form.value.daily_bonus !== null) formData.append('daily_bonus', String(form.value.daily_bonus));
             if (form.value.deposit_bonus !== null) formData.append('deposit_bonus', String(form.value.deposit_bonus));       
           if (props.text_id) {
                await labelStore.update(props.text_id, formData);
            } else {
                await labelStore.store(formData);
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
            const text = labelStore.findLabel(props.text_id);
            if(text){
                form.value = text;
            }
        }
    });

</script>

