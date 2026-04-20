<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import { useModal } from '../../Composables/useModal';
import Icon from '../../Components/Shared/Icon.vue';
import { ArrowPathIcon, PencilSquareIcon, PowerIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Pagination from '../../Components/Shared/Pagination.vue';
import UsuarioForm from '../../Components/Forms/UsuarioForm.vue';
import Badge from '../../Components/UI/Badge.vue';
import UsuarioEstadoForm from '../../Components/Forms/UsuarioEstadoForm.vue';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, usuarios: Object, roles: Object });
const { open } = useModal()

const create = () => {
    open(UsuarioForm, { mode: 'create', roles: props.roles }, 'Crear Usuario');
}

const edit = usuario => {
    open(UsuarioForm, { mode: 'edit', roles: props.roles, data: usuario }, 'Editar Usuario');
}

const deleteForm = usuario => {
    let message = 'Activar Usuario';

    if(usuario.estado === 1){
        message = 'Desactivar Usuario';
    }

    open(UsuarioEstadoForm, { id: usuario.id, estado: usuario.estado }, message, 'sm');
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Usuarios" />

    <TitlePage :breadcrumb="breadcrumb" title="Usuarios"/>

    <NewAction v-if="hasPermission('ver-user')" @open="create" title="Nuevo Usuario" title-button="Crear Usuario"/>

    <ListTable title="Lista de Usuarios" :thead="['Nombre', 'Email', 'Rol', 'Estado', 'Acciones']">
        <tr v-if="usuarios.data.length" v-for="usuario in usuarios.data" :key="usuario.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ usuario.name }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ usuario.email }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ usuario.roles?.[0]?.name }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <Badge :type="usuario.estado === 1 ? 'success' : 'danger'" :text="usuario.estado === 1 ? 'Activo' : 'Inactivo'" />
            </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px] flex justify-center gap-2">
                <Icon v-if="hasPermission('editar-user')" @click="edit(usuario)" :icon="PencilSquareIcon" />
                <Icon v-if="hasPermission('eliminar-user')" @click="deleteForm(usuario)" :icon="usuario.estado === 1 ? PowerIcon : ArrowPathIcon" />
            </td>
        </tr>
       <tr v-else>
            <td colspan="3" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="usuarios.links" :from="usuarios.from" :to="usuarios.to" :total="usuarios.total" />
</template>

<style scoped>
</style>
