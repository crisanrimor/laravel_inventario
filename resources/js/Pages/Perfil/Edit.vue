<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import Icon from '../../Components/Shared/Icon.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import InputLabel from '../../Components/UI/InputLabel.vue';
import InputFile from '../../Components/UI/InputFile.vue';
import { ref } from 'vue';

const props = defineProps({ breadcrumb: Array });
const keyFile = ref(0);

const formPassword = useForm({
    password: '',
    password_confirmation: '',
    password_actual: ''
});

const formImagen = useForm({
    image: '',
});

const handleFile = file => {
    if(file.length){
        formImagen.image = file[0];
    }
}

const changePassword = () => {
    formPassword.patch(route('dashboard.perfil.password'), {
        onSuccess: () => {
            formPassword.reset();
        }
    })
}

const changeImage = () => {
    formImagen.transform(data=> ({
       ...data,
       _method: 'patch'
    }))
    .post(route('dashboard.perfil.imagen'), {
        onSuccess: () => {
            formImagen.reset();
            keyFile.value++;
        }
    })
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Perfil" />

    <TitlePage :breadcrumb="breadcrumb" title="Editar Perfil"/>

    <section class="space-y-10">
        <div class="max-w-[500px] mx-auto p-6 bg-light-card dark:bg-dark-card border border-slate-200 dark:border-dark-border/5 shadow-light-card">
            <p class="text-2xl text-light-text-body dark:text-dark-text-body font-bold tracking-tighter">Actualizar Imagen</p>
            <form @submit.prevent="changeImage" class="flex flex-col mt-5 space-y-4">
                <InputFile @selected="handleFile" label="Imagen" name="image" :error="formImagen.errors.image" :file-key="keyFile"/>

                <button :disabled="formImagen.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ formImagen.processing ? 'Actualizando' : 'Actualizar' }}</button>
            </form>
        </div>

        <div class="max-w-[500px] mx-auto p-6 bg-light-card dark:bg-dark-card border border-slate-200 dark:border-dark-border/5 shadow-light-card">
            <p class="text-2xl text-light-text-body dark:text-dark-text-body font-bold tracking-tighter">Actualizar Contraseña</p>
            <form @submit.prevent="changePassword" class="flex flex-col mt-5 space-y-4">
                <InputLabel type="password" label="Contraseña Actual" placeholder="Ingresa tu contraseña actual" name="password_actual" :error="formPassword.errors.password_actual" v-model="formPassword.password_actual"/>
                <InputLabel type="password" label="Nueva Contraseña" placeholder="Ingresa tu nueva contraseña" name="password" :error="formPassword.errors.password" v-model="formPassword.password"/>
                <InputLabel type="password" label="Confirmar Contraseña" placeholder="Confirma tu nueva contraseña" name="password_confirmation" :error="formPassword.errors.password_confirmation" v-model="formPassword.password_confirmation"/>

                <button :disabled="formPassword.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ formPassword.processing ? 'Actualizando' : 'Actualizar' }}</button>
            </form>
        </div>
    </section>

</template>

<style scoped>
</style>
