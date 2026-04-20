<script setup>
import { Head, Link } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import Icon from '../../Components/Shared/Icon.vue';
import { ArrowsPointingInIcon } from '@heroicons/vue/24/outline';
import Pagination from '../../Components/Shared/Pagination.vue';
import { formatCurrency } from '../../Utils/currency';
import { formatDate } from '../../Utils/date';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, compras: Object });

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Compras" />

    <TitlePage :breadcrumb="breadcrumb" title="Compras"/>

    <NewAction v-if="hasPermission('crear-compra')" :modal="false" title="Nueva Compra" :route="route('dashboard.compras.create')" title-button="Crear Compra"/>

    <ListTable title="Lista de Compras" :thead="['Proveedor', 'Subtotal', 'Impuesto', 'Total', 'Fecha', 'Acciones']">
        <tr v-if="compras.data.length" v-for="compra in compras.data" :key="compra.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ compra.proveedor.nombre }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(compra.subtotal) }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(compra.impuesto) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(compra.total) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatDate(compra.created_at) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <div class="flex justify-center gap-2">
                    <Link v-if="hasPermission('mostrar-compra')" :href="route('dashboard.compras.show', compra.id)"><Icon :icon="ArrowsPointingInIcon" /></Link>
                </div>
            </td>
        </tr>
       <tr v-else>
            <td colspan="7" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="compras.links" :from="compras.from" :to="compras.to" :total="compras.total" />
</template>

<style scoped>
</style>
