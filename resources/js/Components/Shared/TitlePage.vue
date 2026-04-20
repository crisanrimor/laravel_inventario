<script setup>
import BreadCrumb from './BreadCrumb.vue';
const props = defineProps({ breadcrumb: {type: Array, default: null}, title: String });
</script>

<template>
    <div class="flex flex-col md:flex-row justify-between mb-10">
        <h2 class="text-2xl font-bold text-light-text-title dark:text-dark-text-body tracking-tighter">{{ title }}</h2>
        <BreadCrumb v-if="breadcrumb" :breadcrumb="breadcrumb"/>
    </div>
</template>

<style scoped>
</style>
