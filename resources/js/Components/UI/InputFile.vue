<script setup>
const props = defineProps({
    label: String,
    name: String,
    error: String,
    fileKey: Number
});

const emit = defineEmits(['selected']);

const selectFile = e => {
    emit('selected', e.target.files);
}
</script>

<template>
    <div class="flex flex-col space-y-1 w-full">
        <label :for="name" class="text-light-text-muted dark:text-dark-text-muted font-bold text-[14px]">{{ label }}:</label>
        <input @change="selectFile" :key="fileKey" :name="name" :id="name" type="file" class="block w-full text-sm text-light-text-muted dark:text-dark-text-muted border rounded-md cursor-pointer bg-light-base dark:bg-dark-base focus:outline-none focus:ring-2 focus:bg-light-action-primary file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:bg-dark-action-primary file:text-white hover:file:bg-light-action-primary" :class="error ? 'border-red-500' : 'border-slate-200 dark:border-dark-border/5'" />
        <small v-if="error" class="text-red-500 tracking-tighter text-[12px]">{{ error }}</small>
    </div>
</template>

<style scoped>
</style>
