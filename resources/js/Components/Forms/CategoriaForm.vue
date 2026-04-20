<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '../UI/InputLabel.vue';
import { onMounted } from 'vue';

const form = useForm({
    nombre: '',
    descripcion: ''
});

const props = defineProps({ mode: String, data: Object });
const emit = defineEmits(['close']);

const buttonText = props.mode === 'create' ? 'Guardar' : 'Actualizar';
const buttonTextProcess = props.mode === 'create' ? 'Guardando...' : 'Actualizando...'

const submit = () => {
    if(props.mode == 'create'){
        form.post(route('dashboard.categorias.store'), {
            onSuccess: () => {
                emit('close');
            }
        });
    }else{
        form.put(route('dashboard.categorias.update', props.data.id), {
            onSuccess: () => {
                emit('close');
            }
        });
    }
}

onMounted(() => {
    if(props.mode == 'edit'){
        form.nombre = props.data.nombre;
        form.descripcion = props.data.descripcion;
    }
});
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col space-y-4">
        <InputLabel label="Nombre" placeholder="Ingresa nombre de categoria" name="nombre" :error="form.errors.nombre" v-model="form.nombre"/>
        <InputLabel label="Descripción" placeholder="Ingresa descripción de categoria" name="descripcion" :error="form.errors.descripcion" v-model="form.descripcion"/>

        <button :disabled="form.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ form.processing ? buttonTextProcess : buttonText }}</button>
    </form>
</template>

<style scoped>
</style>
