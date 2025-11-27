<template>
    <div v-if="visible" class="fixed inset-0 z-100 bg-red-800">
        <button @click="closeIframe" class="fixed top-4 right-4 z-100 bg-red-900 text-white"> X </button>

        <iframe
            ref="frame"
            :src="url"
            class="w-full h-full border-none"
            allowfullscreen
        ></iframe>
    </div>
</template>

<script setup>
import { ref } from 'vue';
const props = defineProps({
  url: String,
  visible: Boolean
});
const emit = defineEmits(['close']);
const frame = ref(null);

function closeIframe() {
  emit('close');
  if (document.fullscreenElement) {
    document.exitFullscreen();
  }
}

function enterFullscreen() {
  frame.value?.requestFullscreen?.();
}

defineExpose({ enterFullscreen });
</script>