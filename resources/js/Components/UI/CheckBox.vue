<script setup>
import { computed } from 'vue'

const props = defineProps({
    title: String,
    value: [String, Number],
    modelValue: Array
})

const emit = defineEmits(['update:modelValue'])

const isChecked = computed(() => props.modelValue.includes(props.value));

const toggle = () => {
    let newValue

    if(isChecked.value){
        newValue = props.modelValue.filter(v => v !== props.value)
    } else {
        newValue = [...props.modelValue, props.value]
    }

    emit('update:modelValue', newValue)
}
</script>

<template>
    <label @click="toggle" class="flex items-center cursor-pointer">
        <div class="w-11 h-6 bg-gray-300 rounded-full transition relative" :class="{'bg-green-500': isChecked}">
            <div class="absolute left-0.5 top-0.5 w-5 h-5 bg-white rounded-full transition-transform transform" :class="{'translate-x-5': isChecked}"></div>
        </div>
        <span class="text-[14px] ml-4 text-light-text-muted dark:text-dark-text-muted">{{ title }}</span>
    </label>
</template>

<style scoped>
</style>
