<script setup>
const props = defineProps({type: String, message: String});
</script>

<template>
    <div v-if="type === 'success'" class="bg-green-100 border-l-4 border-green-500 text-green-700 px-4 py-3 mb-4" role="alert">
        <p class="font-bold">Éxito!</p>
        <p class="text-[14px]">{{ message }}</p>
    </div>

    <div v-else-if="type === 'error'" class="bg-red-100 border-l-4 border-red-500 text-red-700 px-4 py-3 mb-4" role="alert">
        <p class="font-bold">Error!</p>
        <p class="text-[14px]">{{ message }}</p>
    </div>

    <div v-else-if="type === 'warning'" class="bg-orange-100 border-l-4 border-orange-500 text-orange-700 px-4 py-3 mb-4" role="alert">
        <p class="font-bold">Advertencia!</p>
        <p class="text-[14px]">{{ message }}</p>
    </div>
</template>

<style scoped>
</style>
