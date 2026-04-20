<script setup>
import { computed, onMounted, ref } from 'vue';
import InputLabel from '../../Components/UI/InputLabel.vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import Icon from '../../Components/Shared/Icon.vue';
import { ArrowRightCircleIcon, HomeModernIcon } from '@heroicons/vue/24/solid';
import { BuildingStorefrontIcon } from '@heroicons/vue/24/outline';
import Alerts from '../../Components/UI/Alerts.vue';

const form = useForm({
    'email': '',
    'password': ''
});

const isDark = ref(false);

const page = usePage();
const flash = computed(() => page.props.flash);

const alertType = computed(() => {
    if (flash.value.success) return 'success'
    if (flash.value.error) return 'error'
    if (flash.value.warning) return 'warning'
    return null
})

const alertMessage = computed(() => {
    return (flash.value.success || flash.value.error || flash.value.warning || null);
})

const login = () => {
    form.post(route('auth.login'));
}

onMounted(() => {
    const savedTheme = localStorage.getItem('theme')

    if (savedTheme) {
        isDark.value = savedTheme === 'dark'
    } else {
        isDark.value = window.matchMedia('(prefers-color-scheme: dark)').matches
    }

    if (isDark.value) {
        document.documentElement.classList.add('dark')
    }
});
</script>

<template>
    <Head title="Iniciar sesión" />

    <section class="bg-light-base dark:bg-dark-base min-h-screen flex">
        <div class="m-auto w-[90%] max-w-[400px]">
            <div class="mb-2 flex flex-col items-center space-y-2">
                <Icon :icon="BuildingStorefrontIcon" class="w-18 h-18 text-light-text-title dark:text-dark-text-body"/>
                <h1 class="text-light-text-title text-[18px] font-black tracking-tighter uppercase dark:text-dark-text-title md:text-2xl">Sistema Inventarios</h1>
            </div>

            <div class="bg-light-card dark:bg-dark-card p-8 border border-slate-200 dark:border-none shadow-light-card">
                <Alerts :type="alertType" :message="alertMessage" />
                <div class="mb-6">
                    <h2 class="text-2xl text-light-text-body dark:text-dark-text-title tracking-tighter font-bold">Iniciar sesión</h2>
                    <p class="text-[14px] text-light-text-muted dark:text-dark-text-muted">Ingresa las credenciales para acceder al sistema.</p>
                </div>

                <form @submit.prevent="login" class="space-y-4">
                    <InputLabel type="email" label="Email" placeholder="Ingresa correo eléctronico" name="email" :error="form.errors.email" v-model="form.email"/>
                    <InputLabel type="password" label="Contraseña" placeholder="Ingresa tu contraseña" name="password" :error="form.errors.password" v-model="form.password"/>
                    <button type="submit" class="flex justify-center items-center shadow-light-card gap-2 w-full text-white cursor-pointer py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">
                        <p class="text-[14px] uppercase font-bold tracking-wider">Ingresar</p>
                        <Icon :icon="ArrowRightCircleIcon" />
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>

<style scoped>
</style>
