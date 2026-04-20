<script setup>
import { computed } from 'vue';
import Icon from './Icon.vue';

const props = defineProps({
  icon: {
    type: [Object, Function],
    required: true
  },
  type: String,
  title: String,
  text: [String, Number]
});

const styles = {
  success: 'bg-teal-100 text-teal-800 dark:bg-teal-500/20 dark:text-teal-400',
  danger: 'bg-red-100 text-red-800 dark:bg-red-500/20 dark:text-red-400',
  primary: 'bg-blue-100 text-blue-800 dark:bg-blue-500/20 dark:text-blue-400',
  warning: 'bg-orange-100 text-orange-800 dark:bg-orange-500/20 dark:text-orange-400',
  violet: 'bg-violet-100 text-violet-800 dark:bg-violet-500/20 dark:text-violet-400'
}

const styleIcon = computed(() => styles[props.type]);
</script>

<template>
    <div class="px-8 py-6 border border-slate-200 dark:border-dark-border/5 rounded-lg bg-light-card dark:bg-dark-card">
        <Icon :icon="icon" class="p-2 w-10 h-10 rounded-lg" :class="styleIcon"/>
        <div class="mt-5">
            <h3 class="text-light-text-title dark:text-dark-text-title tracking-tight font-bold">{{ title }}</h3>
            <p class="text-[14px] text-light-text-muted dark:text-dark-text-muted">{{ text }}</p>
            <slot name="content"></slot>
        </div>
    </div>
</template>

<style scoped>
</style>
