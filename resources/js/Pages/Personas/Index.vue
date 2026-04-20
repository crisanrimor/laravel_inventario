<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import PersonaForm from '../../Components/Forms/PersonaForm.vue';
import { useModal } from '../../Composables/useModal';
import Icon from '../../Components/Shared/Icon.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import DeleteForm from '../../Components/Forms/DeleteForm.vue';
import Pagination from '../../Components/Shared/Pagination.vue';
import Badge from '../../Components/UI/Badge.vue';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, personas: Object });
const { open } = useModal()

const create = () => {
    open(PersonaForm, { mode: 'create' }, 'Crear Persona');
}

const edit = persona => {
    open(PersonaForm, { mode: 'edit', data: persona }, 'Editar Persona');
}

const deleteForm = persona => {
    open(DeleteForm, { data: persona.id, route: 'dashboard.personas.destroy'}, 'Eliminar Persona', 'sm');
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Personas" />

    <TitlePage :breadcrumb="breadcrumb" title="Personas"/>

    <NewAction v-if="hasPermission('crear-persona')" @open="create" title="Nueva Persona" title-button="Crear Persona"/>

    <ListTable title="Lista de Personas" :thead="['Nombre', 'Email', 'Teléfono', 'Dirección', 'Tipo Persona', 'Acciones']">
        <tr v-if="personas.data.length" v-for="persona in personas.data" :key="persona.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ persona.nombre }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ persona.email }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ persona.telefono }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ persona.direccion }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <Badge :type="persona.tipo_persona === 'cliente' ? 'success' : 'primary'" :text="persona.tipo_persona" />
            </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px] flex justify-center gap-2">
                <Icon v-if="hasPermission('editar-persona')" @click="edit(persona)" :icon="PencilSquareIcon" />
                <Icon v-if="hasPermission('eliminar-persona')" @click="deleteForm(persona)" :icon="TrashIcon" />
            </td>
        </tr>
       <tr v-else>
            <td colspan="6" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="personas.links" :from="personas.from" :to="personas.to" :total="personas.total" />
</template>

<style scoped>
</style>
