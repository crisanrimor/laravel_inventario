<script setup>
import Icon from '../Shared/Icon.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    icon: {
        type: [Object, Function],
        required: true
    },
    route: String,
    description: String,
    active: Boolean
});
</script>

<template>
    <Link :href="route" class="flex items-center gap-2.5 px-6 py-3 cursor-pointer group transition-all duration-300" :class="active ? 'bg-blue-50 border-l-4 border-light-action-primary dark:bg-transparent dark:bg-linear-to-r from-blue-500/20 to-transparent hover:translate-x-1' : 'hover:bg-white dark:hover:bg-[#222a3d]'">
        <Icon :icon="icon" :class="active ? 'text-light-action-primary dark:text-dark-text-body' : 'group-hover:text-light-text-title text-light-text-muted dark:text-dark-text-muted'" />
        <p class="text-[14px]" :class="active ? 'text-light-action-primary dark:text-dark-text-body' : 'group-hover:text-light-text-title text-light-text-muted dark:text-dark-text-muted'">{{description}}</p>
    </Link>
</template>

<style scoped>
</style>
