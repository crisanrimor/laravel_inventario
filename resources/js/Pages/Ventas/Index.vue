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

const props = defineProps({ breadcrumb: Array, ventas: Object });

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Ventas" />

    <TitlePage :breadcrumb="breadcrumb" title="Ventas"/>

    <NewAction v-if="hasPermission('crear-venta')" :modal="false" :route="route('dashboard.ventas.create')" title="Nueva Venta" title-button="Crear Venta"/>

    <ListTable title="Lista de Ventas" :thead="['Cliente', 'Subtotal', 'Descuento', 'Impuesto', 'Total', 'Fecha', 'Acciones']">
        <tr v-if="ventas.data.length" v-for="venta in ventas.data" :key="venta.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ venta.cliente.nombre }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(venta.subtotal) }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(venta.descuento) }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(venta.impuesto) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(venta.total) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatDate(venta.created_at) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <div class="flex justify-center gap-2">
                    <Link v-if="hasPermission('mostrar-venta')" :href="route('dashboard.ventas.show', venta.id)"><Icon :icon="ArrowsPointingInIcon" /></Link>
                </div>
            </td>
        </tr>
       <tr v-else>
            <td colspan="7" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="ventas.links" :from="ventas.from" :to="ventas.to" :total="ventas.total" />
</template>

<style scoped>
</style>
