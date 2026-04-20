<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ id: Number, estado: Number });
const emit = defineEmits(['close']);

const form = useForm();

const status = computed(() => {
    const isActive = props.estado !== 0;

    return {
        classButton: isActive
            ? 'bg-red-500 hover:brightness-90 dark:hover:bg-red-500/60'
            : 'bg-green-900 hover:brightness-90 dark:hover:bg-green-900/60',
        message: isActive ? 'desactivar' : 'activar'
    };
});

const submit = () => {
    form.patch(route('dashboard.usuarios.updateEstado', props.id), {
        onSuccess: () => {
            emit('close');
        }
    });
}

const close = () => {
    emit('close')
}
</script>

<template>
    <div>
        <p class="text-center text-light-text-body dark:text-dark-text-muted text-[14px]">¿Estás seguro que quieres {{ status.message }} el usuario?</p>

        <div class="mt-5 flex justify-center gap-2.5">
            <button @click="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 transition-all duration-200 capitalize" :class="status.classButton">{{ status.message }}</button>

            <button @click="close" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">Cancelar</button>
        </div>
    </div>
</template>

<style scoped>
</style>
