<script setup>
import { Head, Link } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import Icon from '../../Components/Shared/Icon.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Pagination from '../../Components/Shared/Pagination.vue';
import { useModal } from '../../Composables/useModal';
import DeleteForm from '../../Components/Forms/DeleteForm.vue';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, roles: Object });

const { open } = useModal();

const deleteForm = rol => {
    open(DeleteForm, { data: rol.id, route: 'dashboard.roles.destroy'}, 'Eliminar Rol', 'sm');
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Roles" />

    <TitlePage :breadcrumb="breadcrumb" title="Roles"/>

    <NewAction v-if="hasPermission('crear-role')" title="Nuevo Rol" :route="route('dashboard.roles.create')" title-button="Crear Rol" :modal="false"/>

    <ListTable title="Lista de Roles" :thead="['Nombre', 'Acciones']">
        <tr v-if="roles.data.length" v-for="rol in roles.data" :key="roles.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ rol.name }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px] flex justify-center gap-2">
                <Link v-if="hasPermission('editar-role')" :href="route('dashboard.roles.edit', rol.id)">
                    <Icon :icon="PencilSquareIcon" />
                </Link>
                <Icon v-if="hasPermission('eliminar-role')" @click="deleteForm(rol)" :icon="TrashIcon" />
            </td>
        </tr>
       <tr v-else>
            <td colspan="3" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="roles.links" :from="roles.from" :to="roles.to" :total="roles.total" />
</template>

<style scoped>
</style>
