<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '../UI/InputLabel.vue';
import { onMounted } from 'vue';
import SelectInput from '../UI/SelectInput.vue';

const form = useForm({
    nombre: '',
    email: '',
    telefono: '',
    direccion: '',
    tipo_persona: ''
});

const props = defineProps({ mode: String, data: Object });
const emit = defineEmits(['close']);
const buttonText = props.mode === 'create' ? 'Guardar' : 'Actualizar';
const buttonTextProcess = props.mode === 'create' ? 'Guardando...' : 'Actualizando...'

const submit = () => {
    if(props.mode == 'create'){
        form.post(route('dashboard.personas.store'), {
            onSuccess: () => {
                emit('close');
            }
        });
    }else{
        form.put(route('dashboard.personas.update', props.data.id), {
            onSuccess: () => {
                emit('close');
            }
        });
    }
}

const tipoPersona = [
    { id: 'cliente', nombre: 'Cliente' },
    { id: 'proveedor', nombre: 'Proveedor' }
]

onMounted(() => {
    if(props.mode == 'edit'){
        form.nombre = props.data.nombre;
        form.email = props.data.email;
        form.telefono = props.data.telefono;
        form.direccion = props.data.direccion;
        form.tipo_persona = props.data.tipo_persona;
    }
});
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col space-y-4">
        <InputLabel label="Nombre" placeholder="Ingresa nombre de persona" name="nombre" :error="form.errors.nombre" v-model="form.nombre"/>
        <InputLabel type="email" label="Email" placeholder="Ingresa email de persona" name="email" :error="form.errors.email" v-model="form.email"/>
        <InputLabel label="Teléfono" placeholder="Ingresa teléfono de persona" name="telefono" :error="form.errors.telefono" v-model="form.telefono"/>
        <InputLabel label="Dirección" placeholder="Ingresa dirección de persona" name="direccion" :error="form.errors.direccion" v-model="form.direccion"/>
        <SelectInput :data="tipoPersona" label="Tipo Persona" name="tipo_persona" :error="form.errors.tipo_persona" v-model="form.tipo_persona" />

        <button :disabled="form.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ form.processing ? buttonTextProcess : buttonText }}</button>
    </form>
</template>

<style scoped>
</style>
