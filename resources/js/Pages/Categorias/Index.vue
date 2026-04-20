<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import CategoriaForm from '../../Components/Forms/CategoriaForm.vue';
import { useModal } from '../../Composables/useModal';
import Icon from '../../Components/Shared/Icon.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import DeleteForm from '../../Components/Forms/DeleteForm.vue';
import Pagination from '../../Components/Shared/Pagination.vue';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, categorias: Object });
const { open } = useModal()

const create = () => {
    open(CategoriaForm, { mode: 'create' }, 'Crear Categoria');
}

const edit = categoria => {
    open(CategoriaForm, { mode: 'edit', data: categoria }, 'Editar Categoria');
}

const deleteForm = categoria => {
    open(DeleteForm, { data: categoria.id, route: 'dashboard.categorias.destroy'}, 'Eliminar Categoria', 'sm');
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Categorias" />

    <TitlePage :breadcrumb="breadcrumb" title="Categorias"/>

    <NewAction v-if="hasPermission('crear-categoria')" @open="create" title="Nueva Categoria" title-button="Crear Categoria"/>

    <ListTable title="Lista de Categorias" :thead="['Nombre', 'Descripción', 'Acciones']">
        <tr v-if="categorias.data.length" v-for="categoria in categorias.data" :key="categoria.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ categoria.nombre }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ categoria.descripcion ?? 'Sin descripción' }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px] flex justify-center gap-2">
                <Icon v-if="hasPermission('editar-categoria')" @click="edit(categoria)" :icon="PencilSquareIcon" />
                <Icon v-if="hasPermission('eliminar-categoria')" @click="deleteForm(categoria)" :icon="TrashIcon" />
            </td>
        </tr>
       <tr v-else>
            <td colspan="3" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="categorias.links" :from="categorias.from" :to="categorias.to" :total="categorias.total" />
</template>

<style scoped>
</style>
