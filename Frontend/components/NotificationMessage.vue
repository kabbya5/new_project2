<template>
    <div v-if="visible" class="notification" :class="type" @click="dismiss">
      <p class="font-xl leading-5 tracking-wide">{{ type + ' ' + message }}</p>
    </div>
</template>

<script setup lang="ts">
    const props = defineProps({
        message:{
            type:String,
            required:true,
        },
        type:{
            type:String,
            default:'info',
        },
    });

    const emit = defineEmits(['dismiss']);

    const visible = ref(true);

    const dismiss = () =>{
        visible.value = false;
        emit('dismiss');
    }
</script>

<style scoped>
.notification {
  position: fixed;
  top: 20px;
  right: 20px;
  padding: 15px;
  border-radius: 5px;
  transition: opacity 0.3s ease;
  z-index: 100;
}

.notification.info {
  background-color: #2196f3;
  color: white;
}

.notification.success {
  background-color: #4caf50;
  color: white;
}

.notification.error {
  background-color: #f44336;
  color: white;
}

.notification.warning {
  background-color: #ff9800;
  color: white;
}
</style>