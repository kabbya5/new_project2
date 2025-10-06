<template>
  <div class="marquee">
    <marquee behavior="scroll" direction="left" scrollamount="6" class="text-gray-700 dark:text-gray-300">
       {{ items.map(item => item.text).join(' • ') }}
    </marquee>
  </div>
</template>

<script setup lang="ts">

const items = ref<string[]>([]);

onMounted(async () => {
  try {
    const data = await useApiFetch('/marquee');
    if (data.texts) {
      items.value = data.texts
    }
  } catch (e) {
    console.error('Failed to load marquee text', e)
  }
})
</script>

<style scoped>
.marquee {
  font-size: 1.2rem;
  color: #fff;
  padding: 10px;
  border-radius: 8px;
}
</style>
