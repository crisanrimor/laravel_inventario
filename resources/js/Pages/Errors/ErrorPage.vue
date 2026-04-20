<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';
import Icon from '../../Components/Shared/Icon.vue';
import { ArrowUturnLeftIcon } from '@heroicons/vue/24/outline';
import { useModal } from '../../Composables/useModal';

const props = defineProps({ status: Number });
const isDark = ref(false);
const { close } = useModal();

const mensajes = {
    403: { titulo: 'Acceso denegado',   descripcion: 'No tienes permiso para ver esta página.', img: '/storage/error/error403.webp' },
    404: { titulo: 'Página no encontrada', descripcion: 'La página que buscas no existe.', img: '/storage/error/error404.png' },
    default: { titulo: 'Algo salió mal', descripcion: 'Ocurrió un error inesperado.', img: '/storage/error/wrong.webp' }
};

const { titulo, descripcion, img } = mensajes[props.status] ?? mensajes['default'];

onMounted(() => {
    close(); // Cerrar modal

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
    <Head :title="titulo" />

    <section class="min-h-screen bg-light-base dark:bg-dark-base flex">
        <div class="m-auto flex flex-col items-center">
            <figure class="w-[90%] max-w-[600px]">
                <img :src="img" class="w-full h-full object-cover" :alt="titulo">
            </figure>

            <div class="text-center">
                <h1 class="text-2xl font-bold tracking-tighter uppercase text-light-text-body dark:text-dark-text-body">{{ titulo }}</h1>
                <p class="text-[14px] text-light-text-muted dark:text-dark-text-muted tracking-wider">{{ descripcion }}</p>
                <Link :href="route('dashboard.home')" class="mt-4 inline-flex gap-1.5 items-center rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200 cursor-pointer">
                    <Icon :icon="ArrowUturnLeftIcon" class="text-white"/>
                    <p class="text-white text-[14px]">Regresar</p>
                </Link>
            </div>
        </div>
    </section>
</template>
