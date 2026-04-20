<script setup>
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ links: Array, from: Number, to: Number, total: Number });
const validLinks = computed(() => {
    return props.links.filter(link => link.url)
});
</script>

<template>
    <div v-if="total > 0" class="flex flex-col gap-4 justify-between items-center mt-4 md:flex-row md:gap-0">
        <p class="text-light-text-muted dark:text-dark-text-muted text-[14px]">
            Mostrando {{ from ?? 0 }} a {{ to ?? 0 }} de {{ total }} registros
        </p>

        <div>
            <template v-for="(link, index) in validLinks" :key="index">
                <Link
                    class="text-[14px] px-3 py-2 border border-slate-200 dark:border-dark-border/5"
                    :href="link.url ? link.url : ''"
                    v-html="link.label"
                    :class="[
                        link.active ? 'bg-dark-action-primary text-white' : 'bg-light-card dark:bg-dark-card text-light-text-muted dark:text-dark-text-muted',
                        index === 0 ? 'rounded-l-lg' : '',
                        index === validLinks.length - 1 ? 'rounded-r-lg' : ''
                    ]"
                />
            </template>
        </div>
    </div>
</template>

<style scoped>
</style>
