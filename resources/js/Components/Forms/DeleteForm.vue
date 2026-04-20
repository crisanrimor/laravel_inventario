<script setup>
import { useForm } from '@inertiajs/vue3';

const props = defineProps({ data: Number, route: String });
const emit = defineEmits(['close']);

const form = useForm();

const submit = () => {
    form.delete(route(props.route, props.data), {
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
        <p class="text-center text-light-text-body dark:text-dark-text-muted text-[14px]">¿Estás seguro que quieres eliminar el registro?</p>

        <div class="mt-5 flex justify-center gap-2.5">
            <button @click="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-red-500 hover:brightness-90 dark:hover:bg-red-500/60 transition-all duration-200">Eliminar</button>

            <button @click="close" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">Cancelar</button>
        </div>
    </div>
</template>

<style scoped>
</style>
