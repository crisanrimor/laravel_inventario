<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({breadcrumb: Array});
</script>

<template>
    <nav class="flex space-x-1">
        <Link href="/" class="flex items-center space-x-1">
            <p class="text-[13px] text-light-text-muted dark:text-dark-text-muted underline">Panel</p>
            <span class="text-[13px] text-light-text-muted dark:text-dark-text-muted">></span>
        </Link>
        <Link v-for="(item, index) in breadcrumb" :href="item.url" :key="index" class="flex items-center space-x-1">
            <p class="text-[13px] text-light-text-muted dark:text-dark-text-muted" :class="index < breadcrumb.length - 1 ? 'underline' : ''">{{ item.name }}</p>
            <span v-if="index < breadcrumb.length - 1" class="text-[13px] text-light-text-muted dark:text-dark-text-muted">></span>
        </Link>
    </nav>
</template>

<style scoped>
</style>
