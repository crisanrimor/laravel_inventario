<script setup>
const model = defineModel({
    type: [Number, String]
});

const props = defineProps({
    label: String,
    name: String,
    error: String,
    data: Object,
    nombre: {
        type: String,
        default: null
    }
});
</script>

<template>
    <div class="flex flex-col space-y-1 w-full">
        <label :for="name" class="text-light-text-muted dark:text-dark-text-muted font-bold text-[14px]">{{ label }}:</label>
        <select v-model="model" :name="name" :id="name" class="block w-full px-4 py-2 text-[13px] rounded-md bg-light-base dark:bg-dark-base border text-light-text-title dark:text-dark-text-title" :class="error ? 'border-red-500' : 'border-slate-200 dark:border-dark-border/5'">
            <option value="">-- Selecciona una opción --</option>
            <option v-for="value in data" :value="value.id">{{ nombre ? value[nombre] : value.nombre }}</option>
        </select>
        <small v-if="error" class="text-red-500 tracking-tighter text-[12px]">{{ error }}</small>
    </div>
</template>

<style scoped>
</style>
