<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '../UI/InputLabel.vue';
import { onMounted } from 'vue';
import SelectInput from '../UI/SelectInput.vue';

const form = useForm({
    name: '',
    email: '',
    password: '',
    rol: ''
});

const props = defineProps({ mode: String, data: Object, roles: Object });
const emit = defineEmits(['close']);

const buttonText = props.mode === 'create' ? 'Guardar' : 'Actualizar';
const buttonTextProcess = props.mode === 'create' ? 'Guardando...' : 'Actualizando...'

const submit = () => {
    if(props.mode == 'create'){
        form.post(route('dashboard.usuarios.store'), {
            onSuccess: () => {
                emit('close');
            }
        });
    }else{
        form.put(route('dashboard.usuarios.update', props.data.id), {
            onSuccess: () => {
                emit('close');
            }
        });
    }
}

onMounted(() => {
    if(props.mode == 'edit'){
        form.name = props.data.name;
        form.email = props.data.email;
        form.rol = props.data.roles?.[0]?.id;
    }
});
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col space-y-4">
        <InputLabel label="Nombre" placeholder="Ingresa nombre del usuario" name="name" :error="form.errors.name" v-model="form.name"/>
        <InputLabel type="text" label="Email" placeholder="Ingresa email del usuario" name="email" :error="form.errors.email" v-model="form.email"/>
        <InputLabel v-if="mode !== 'edit'" type="password" label="Contraseña" placeholder="Ingresa contraseña del usuario" name="password" :error="form.errors.password" v-model="form.password"/>
        <SelectInput :data="roles" label="Rol" name="rol" :error="form.errors.rol" nombre="name" v-model="form.rol" />

        <button :disabled="form.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ form.processing ? buttonTextProcess : buttonText }}</button>
    </form>
</template>

<style scoped>
</style>
