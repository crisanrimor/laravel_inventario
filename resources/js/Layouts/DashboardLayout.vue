<script setup>
import { computed, onMounted, ref } from 'vue';
import Header from '../Components/Shared/Header.vue';
import Navbar from '../Components/Shared/Navbar.vue';
import BaseModal from '../Components/Shared/BaseModal.vue';
import { usePage } from '@inertiajs/vue3';
import { useModal } from '../Composables/useModal';
import Alerts from '../Components/UI/Alerts.vue';

const isOpen = ref(true);
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
    return (flash.value.success || flash.value.error || flash.value.warning ||null);
})

const { show, component, modalProps, title, defaultSize, close, action } = useModal();

const tooggleSidebar = () => isOpen.value = !isOpen.value;
const toggleDark = () => {
    isDark.value = !isDark.value;

    const html = document.documentElement;

    if(isDark.value){
        html.classList.add('dark');
        localStorage.setItem('theme', 'dark');
    }else{
        html.classList.remove('dark');
        localStorage.setItem('theme', 'light');
    }
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
    <div>
        <Navbar @close="tooggleSidebar" :isOpen="isOpen" />

        <section class="min-h-screen dark:bg-dark-base transition-all duration-300" :class="isOpen ? 'md:ml-72' : ''">
            <Header @open="tooggleSidebar" @toggle-dark="toggleDark" :isDark="isDark"/>
            <main class="py-12 mx-auto w-11/12 max-w-[1400px] min-h-screen">
                <Alerts :type="alertType" :message="alertMessage" />
                <slot></slot>
            </main>
        </section>

        <BaseModal :show="show" :size="defaultSize" :title="title" :component="component" :componentProps="modalProps" @close="close" @action="action" />
    </div>
</template>

<style scoped>
</style>
