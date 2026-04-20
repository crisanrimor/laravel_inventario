<script setup>
import { SunIcon, MoonIcon, Bars3Icon, ChevronDownIcon, Cog6ToothIcon, ArrowRightStartOnRectangleIcon } from '@heroicons/vue/24/outline'
import Icon from './Icon.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { ref, Transition } from 'vue';
import { useClickOutside } from '../../Composables/useClickOutside';

const emit = defineEmits(['open', 'toggleDark']);
const props = defineProps({ isDark: Boolean });
const page = usePage();

const openMenu = ref(false);
const menuRef = ref(null);

const showMenu = () => {
    openMenu.value = !openMenu.value;
}

useClickOutside(menuRef, () => {
    openMenu.value = false;
}, openMenu);

</script>

<template>
    <header class="w-full h-[60px] bg-light-base border-b border-light-text-muted shadow-sm z-1 dark:bg-dark-base/60 border-none ">
        <section class="h-full flex justify-between items-center px-3.5">
            <div class="flex items-center gap-2.5">
                <Icon @click="emit('open')" :icon="Bars3Icon" class="text-light-text-title dark:text-dark-text-body w-7 h-7"/>
            </div>

            <div class="flex gap-5 items-center">
                <Icon v-show="!isDark" @click="emit('toggleDark')" :icon="MoonIcon" class="text-light-text-title dark:text-dark-text-body w-7 h-7 transition duration-300 hover:rotate-360"/>
                <Icon v-show="isDark" @click="emit('toggleDark')" :icon="SunIcon" class="text-light-text-title dark:text-dark-text-body w-7 h-7 transition duration-300 hover:rotate-360"/>

                <div ref="menuRef" class="flex items-center text-light-text-body dark:text-dark-text-body gap-3 relative">
                    <figure class="w-10 h-10 rounded-full overflow-hidden">
                        <img :src="page.props.auth.user.imagen_url" alt="Avatar usuario" class="w-full">
                    </figure>

                    <button @click="showMenu" class="flex items-center gap-2 cursor-pointer">
                        <p class="text-[14px]">{{ page.props.auth.user.name }}</p>
                        <Icon :icon="ChevronDownIcon" class="stroke-3 transition-all" :class="{ 'rotate-180': openMenu }"/>
                    </button>

                    <Transition
                        enter-active-class="transition duration-300 ease-out"
                        enter-from-class="opacity-0 scale-95"
                        enter-to-class="opacity-100 scale-100"
                        leave-active-class="transition duration-100 ease-in"
                        leave-from-class="opacity-100 scale-100"
                        leave-to-class="opacity-0 scale-95"
                    >
                        <div v-show="openMenu" class="rounded-md bg-light-card dark:bg-dark-card z-10 absolute w-[250px] top-[70px] right-0 p-5 shadow-light-card border border-slate-200 dark:border-dark-border/5">
                            <div class="text-light-text-muted dark:text-dark-text-muted text-[12px] pb-4 border-b border-slate-200 dark:border-dark-border/5">
                                <p class="font-bold tracking-tighter">{{ page.props.auth.user.name }}</p>
                                <p class="tracking-wide">{{ page.props.auth.user.email }}</p>
                            </div>

                            <div class="mt-5 space-y-1">
                                <Link :href="route('dashboard.perfil')" class="flex items-center rounded-md p-2 gap-2 text-light-text-body dark:text-dark-text-body hover:bg-light-base dark:hover:bg-[#222a3d]">
                                    <Icon :icon="Cog6ToothIcon" />
                                    <p class="text-[13px] tracking-tight font-bold">Editar Perfil</p>
                                </Link>
                                <Link as="button" :href="route('dashboard.logout')" method="post" class="w-full cursor-pointer flex items-center rounded-md p-2 gap-2 text-light-text-body dark:text-dark-text-body hover:bg-light-base dark:hover:bg-[#222a3d]">
                                    <Icon :icon="ArrowRightStartOnRectangleIcon" />
                                    <p class="text-[13px] tracking-tight font-bold">Cerrar Sesión</p>
                                </Link>
                            </div>
                        </div>
                    </Transition>
                </div>
            </div>
        </section>
    </header>
</template>

<style scoped>
</style>
