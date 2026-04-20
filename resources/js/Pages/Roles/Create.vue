<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import InputLabel from '../../Components/UI/InputLabel.vue';
import CheckBox from '../../Components/UI/CheckBox.vue';

const props = defineProps({ breadcrumb: Array, permisos: Object });
const form = useForm({
    'name': '',
    'permisos': []
});

const submit = () => {
    form.post(route('dashboard.roles.store'));
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Crear Rol" />

    <TitlePage :breadcrumb="breadcrumb" title="Crear Rol"/>

    <div class="max-w-[600px] mx-auto bg-light-card dark:bg-dark-card p-8 border border-slate-200 dark:border-dark-border/5">
        <form @submit.prevent="submit" class="flex flex-col space-y-4">
            <InputLabel label="Nombre" placeholder="Ingresa nombre del rol" name="name" :error="form.errors.name" v-model="form.name"/>

            <div class="mt-2 space-y-2">
                <p class="text-light-text-muted dark:text-dark-text-muted font-bold text-[14px]">Selecciona permisos</p>
                <small v-if="form.errors.permisos" class="text-red-500 tracking-tighter text-[12px]">{{ form.errors.permisos }}</small>

                <div class="flex flex-col items-start gap-3 mt-4">
                    <CheckBox v-for="permiso in permisos" :key="permiso.name" :value="permiso.name" :title="permiso.name" v-model="form.permisos"/>
                </div>
            </div>

            <button :disabled="form.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">Guardar</button>
        </form>
    </div>
</template>

<style scoped>
</style>
