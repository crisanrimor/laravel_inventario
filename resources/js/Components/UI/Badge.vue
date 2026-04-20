<script setup>
import { computed } from 'vue';

const props = defineProps({type: String, text: String, classBadge:{ type: String, default: '' }});

const styles = {
  success: 'bg-teal-100 text-teal-800 dark:bg-teal-500/20 dark:text-teal-400',
  danger: 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
  primary: 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400',
  warning: 'bg-orange-100 text-orange-800 dark:bg-orange-500/20 dark:text-orange-400'
}

const styleBadge = computed(() => styles[props.type]);
</script>

<template>
    <span class="text-xs py-1 px-2 rounded" :class="[styleBadge, classBadge]">{{text}}</span>
</template>

<style scoped>
</style>
