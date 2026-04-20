<script setup>
import { SquaresPlusIcon } from '@heroicons/vue/24/outline'
import Icon from './Icon.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({ title: String, route: {type: String, default: ''}, titleButton: String, modal: { type: Boolean, default: true } })
const emit = defineEmits(['open']);
</script>

<template>
    <section class="rounded-lg border bg-light-base border-slate-200 dark:bg-dark-card dark:border-dark-border/5">
        <div class="p-6 border-b border-slate-200 dark:border-dark-border/5">
            <p class="text-[18px] text-light-text-title dark:text-dark-text-title font-bold">{{ title }}</p>
        </div>

        <div class="p-6">
            <button v-if="modal" @click="emit('open')" class="inline-flex gap-1.5 items-center rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200 cursor-pointer">
                <Icon :icon="SquaresPlusIcon" class="text-white"/>
                <p class="text-white text-[14px]">{{ titleButton }}</p>
            </button>

            <Link v-else :href="route" class="inline-flex gap-1.5 items-center rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200 cursor-pointer">
                <Icon :icon="SquaresPlusIcon" class="text-white"/>
                <p class="text-white text-[14px]">{{ titleButton }}</p>
            </Link>
        </div>
    </section>
</template>

<style scoped>
</style>
