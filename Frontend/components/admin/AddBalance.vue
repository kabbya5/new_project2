<template>
  <div
    v-if="show"
    class="fixed top-12 left-0 w-full flex items-center justify-center bg-black/50 bg-opacity-50 z-50"
  >
    <div class="bg-card rounded-2xl shadow-xl w-96 p-6 relative">
      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-3 right-3 text-2xl"
      >
        ✕
      </button>

      <!-- Title -->
      <h2
        class="text-xl font-bold mb-4 text-center"
      >
        Add Balance to Agent
      </h2>

      <!-- Form -->
      <form v-if="!confirming" @submit.prevent="confirmSubmit">
        <div class="mb-3">
          <label
            class="block text-sm font-medium"
          >
            Amount
          </label>
          <input
            type="number"
            v-model="amount"
            class="w-full mt-1 px-3 py-2 text-gray-600 border border-gray-300 dark:border-gray-700 rounded-lg bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            placeholder="Enter amount"
          />
        </div>

        <div class="flex justify-end gap-2 mt-4">
          <button
            type="button"
            @click="$emit('close')"
            class="px-4 py-2 rounded-lg bg-white text-black"
          >
            Cancel
          </button>
          <button
            type="submit"
            class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold"
          >
            Add
          </button>
        </div>
      </form>

      <!-- Confirmation Dialog -->
      <div v-else class="text-center">
        <p class="text-gray-800 dark:text-gray-100 mb-6">
          Are you sure you want to add
          <span class="font-semibold text-blue-600">{{ amount }}</span>?
        </p>

        <div class="flex justify-center gap-4">
          <button
            @click="confirming = false"
            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-lg"
          >
            No
          </button>
          <button
            @click="submitFinal"
            class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-lg"
          >
            Yes, Confirm
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, defineEmits, defineProps } from "vue";

// Assign props to a variable
const props = defineProps<{
  show: boolean
  userId: number | null
}>()

const emit = defineEmits(["close", "submitted"]);

const amount = ref<number | null>(null);
const confirming = ref(false);

// Step 1: Ask for confirmation
const confirmSubmit = () => {
  if (!amount.value || amount.value <= 0) {
    alert("⚠️ Please enter a valid amount");
    return;
  }
  confirming.value = true;
};

// Step 2: After confirmation
const submitFinal = async () => {
  if (!props.userId) {
    alert("⚠️ User ID not selected!");
    return;
  }

  try {
    const res: any = await useApiFetch(`/admin/agent/add/balance/${props.userId}`, {
      method: "POST",
      body: { amount: amount.value },
    });

    
    confirming.value = false;
    emit("close");
    alert(`✅ ${amount.value} added successfully!`);
    amount.value = null;
  } catch (error: any) {
    console.error("API Error:", error);
    alert(`❌ Something went wrong. ${error?.message || ""}`);
    confirming.value = false;
  }
};
</script>
