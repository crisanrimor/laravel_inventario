<script setup>
import { computed, ref, Transition } from 'vue'
import { XMarkIcon } from '@heroicons/vue/24/outline'
import Icon from './Icon.vue'

const props = defineProps({
    show: Boolean,
    title: String,
    size: String,
    component: [Object, Function],
    componentProps: {
        type: Object,
        default: () => ({})
    }
});

const sizeClasses = {
  sm: 'max-w-sm',
  md: 'max-w-md',
  lg: 'max-w-lg',
  xl: 'max-w-xl',
  xxl: 'max-w-2xl'
}

const sizeModal = computed(() => sizeClasses[props.size])

const emit = defineEmits(['close', 'action'])

const close = () => {
    emit('close')
}

const action = (data = null) => {
    emit('action', data)
}

const closeModal = e => {
    if(e.target === document.getElementById('modalContainer')){
        close();
    }
}

</script>

<template>
    <div v-show="show" class="fixed z-20 inset-0 bg-black/50"></div>

    <Transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 -translate-y-12"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition duration-100 ease-in"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-12"
     >
        <div v-show="show" @click="closeModal" class="fixed inset-0 z-50 flex items-center justify-center" id="modalContainer">
            <div class="relative bg-light-base dark:bg-dark-card rounded-md shadow-lg w-[90%] p-6" :class="sizeModal">
                <div class="flex justify-between items-center mb-4 border-b pb-3 border-slate-200 dark:border-dark-border/5">
                    <h2 class="text-lg font-semibold text-light-text-body dark:text-dark-text-body">{{ title }}</h2>
                    <button @click="close"><Icon :icon="XMarkIcon" class="w-6 h-6 text-light-text-body dark:text-dark-text-body"/></button>
                </div>

                <component
                    :is="component"
                    v-bind="componentProps"
                    @close="close"
                    @action="action"
                />
            </div>
        </div>
    </Transition>
</template>

<style scoped>
</style>
