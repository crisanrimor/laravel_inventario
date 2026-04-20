<script setup>
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';
import Icon from '../Shared/Icon.vue';
import { computed, ref } from 'vue';

const model = defineModel({
    type: [String, Number]
});

const props = defineProps({
    label: String,
    placeholder: {type: String, default: ''},
    name: String,
    error: String,
    type: {
        type: String,
        default: 'text'
    }
});

const inputType = ref(props.type);

const iconInput = computed(() => {
    return inputType.value === 'password' ? EyeIcon : EyeSlashIcon;
});

const toggleInput = () => {
    inputType.value = inputType.value === 'password' ? 'text' : 'password';
}
</script>

<template>
    <div class="flex flex-col space-y-1 w-full">
        <label :for="name" class="text-light-text-muted dark:text-dark-text-muted font-bold text-[14px]">{{ label }}:</label>
        <div class="relative">
            <input v-model="model" :type="inputType" :placeholder="placeholder" :name="name" :id="name" class="w-full px-4 py-2 text-[13px] rounded-md bg-light-base dark:bg-dark-base border text-light-text-title dark:text-dark-text-title outline-none focus:ring-2 focus:ring-dark-action-primary" :class="error ? 'border-red-500' : 'border-slate-200 dark:border-dark-border/5'">

            <Icon @click="toggleInput" v-if="type ==='password'" :icon="iconInput" class="text-light-text-body dark:text-dark-text-body absolute top-1/2 -translate-y-1/2 right-4"/>
        </div>

        <small v-if="error" class="text-red-500 tracking-tighter text-[12px]">{{ error }}</small>
    </div>
</template>

<style scoped>
</style>
