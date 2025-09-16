<template>
  <div class="w-64">
    <multiselect
      v-model="internalValue"
      :options="options"
      :custom-label="customLabel"
      label="label"
      track-by="value"
      :placeholder="placeholder"
      @update:modelValue="updateValue"
      class="custom-multiselect"
    >
      <!-- Dropdown Option -->
      <template #option="{ option }">
        <div class="flex items-center gap-2">
          <img :src="option.image" class="w-6 h-6 rounded" />
          <span>{{ option.label }}</span>
        </div>
      </template>

      <!-- Selected Value -->
      <template #singleLabel="{ option }">
        <div class="flex items-center gap-2">
          <img :src="option.image" class="w-6 h-6 rounded" />
          <span>{{ option.label }}</span>
        </div>
      </template>
    </multiselect>
  </div>
</template>

<script setup>
import Multiselect from "vue-multiselect"
import { ref, watch } from "vue"

const props = defineProps({
  options: { type: Array, required: true },
  selected: { type: Object, default: null },
  placeholder: { type: String, default: "Select option" }
})

const emit = defineEmits(["update:selected"])

const internalValue = ref(props.selected)

watch(
  () => props.selected,
  (newVal) => {
    internalValue.value = newVal
  }
)

function updateValue(val) {
  emit("update:selected", val)
}

function customLabel(option) {
  return option.label
}
</script>



