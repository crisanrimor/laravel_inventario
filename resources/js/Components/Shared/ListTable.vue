<script setup>
const props = defineProps({ title: String, thead: Array });
</script>

<template>
    <section class="mt-10 bg-light-base border border-slate-200 dark:bg-dark-card rounded-lg overflow-x-auto dark:border-dark-border/5">
        <div class="p-6">
            <p class="text-light-text-body dark:text-dark-text-body font-bold text-[18px]">{{ title }}</p>
        </div>

        <table class="w-full min-w-125">
            <thead class="bg-white text-light-text-muted  dark:bg-dark-base dark:text-dark-text-disabled">
                <tr>
                    <th class="uppercase text-[12px] py-4 whitespace-nowrap" v-for="item in thead" :key="item">{{ item }}</th>
                </tr>
            </thead>

            <tbody class="text-center divide-y divide-white/5">
                <slot></slot>
            </tbody>
        </table>
    </section>
</template>

<style scoped>
</style>
