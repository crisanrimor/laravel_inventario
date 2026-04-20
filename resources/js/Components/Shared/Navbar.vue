<script setup>
import { ListBulletIcon, CubeIcon, ShoppingCartIcon, CreditCardIcon, TableCellsIcon, UserPlusIcon, UsersIcon, CommandLineIcon, XMarkIcon, HomeIcon } from '@heroicons/vue/24/outline'
import NavItem from '../UI/NavItem.vue';
import Icon from './Icon.vue';
import { usePage } from '@inertiajs/vue3';
import { hasPermission } from '../../Utils/permission';

const emit = defineEmits(['close']);
const props = defineProps({ isOpen: Boolean });

const page = usePage();

const isActive = route => {
    if(route === 'home' && page.url === '/dashboard') return true;
    return page.url.includes(route)
}
</script>

<template>
    <aside class="fixed inset-y-0 left-0 w-60 z-10 bg-light-base border-r border-slate-200 overflow-hidden pt-4 md:w-72 dark:bg-dark-base dark:border-dark-border/5 transition-transform duration-300" :class="isOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="px-6 py-2 flex justify-between items-center">
            <p class="text-light-text-title text-[18px] font-black tracking-tighter uppercase dark:text-dark-text-body md:text-2xl">Inventarios</p>
            <Icon @click="emit('close')" :icon="XMarkIcon" class="w-6 h-6 text-light-text-title dark:text-dark-text-body md:hidden"/>
        </div>

        <div class="mt-10">
            <p class="uppercase px-6 mb-2 text-light-text-muted tracking-wider text-[13px] dark:text-dark-text-body">Menu</p>

            <div class="space-y-1">
                <NavItem :href="route('dashboard.home')" :icon="HomeIcon" description="Panel" :active="isActive('home')"/>
                <NavItem v-if="hasPermission('ver-categoria')" :href="route('dashboard.categorias.index')" :icon="ListBulletIcon" description="Categorias" :active="isActive('categorias')"/>
                <NavItem v-if="hasPermission('ver-producto')" :href="route('dashboard.productos.index')" :icon="CubeIcon" description="Productos" :active="isActive('productos')"/>
                <NavItem v-if="hasPermission('ver-compra')" :href="route('dashboard.compras.index')" :icon="ShoppingCartIcon" description="Compras" :active="isActive('compras')"/>
                <NavItem v-if="hasPermission('ver-venta')" :href="route('dashboard.ventas.index')" :icon="CreditCardIcon" description="Ventas" :active="isActive('ventas')"/>
                <NavItem v-if="hasPermission('ver-movimiento')" :href="route('dashboard.movimientos')" :icon="TableCellsIcon" description="Movimientos" :active="isActive('movimientos')"/>
                <NavItem v-if="hasPermission('ver-persona')" :href="route('dashboard.personas.index')" :icon="UserPlusIcon" description="Personas" :active="isActive('personas')"/>
            </div>
        </div>

        <div class="mt-5" v-if="hasPermission('ver-user') || hasPermission('ver-role')">
            <p class="uppercase px-6 mb-2 text-light-text-muted tracking-wider text-[13px] dark:text-dark-text-body">Otros</p>

            <div class="space-y-1">
                <NavItem v-if="hasPermission('ver-user')" :href="route('dashboard.usuarios.index')" :icon="UsersIcon" description="Usuarios" :active="isActive('usuarios')"/>
                <NavItem v-if="hasPermission('ver-role')" :href="route('dashboard.roles.index')" :icon="CommandLineIcon" description="Roles" :active="isActive('roles')"/>
            </div>
        </div>
    </aside>
</template>

<style scoped>
</style>
