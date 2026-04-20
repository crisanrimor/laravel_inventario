<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import Pagination from '../../Components/Shared/Pagination.vue';
import { formatDate } from '../../Utils/date';
import Badge from '../../Components/UI/Badge.vue';

const props = defineProps({ breadcrumb: Array, movimientos: Object });

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Movimientos" />

    <TitlePage :breadcrumb="breadcrumb" title="Movimientos"/>

    <ListTable title="Lista de Movimientos" :thead="['Producto', 'Tipo', 'Cantidad', 'Fecha Movimiento']">
        <tr v-if="movimientos.data.length" v-for="movimiento in movimientos.data" :key="movimiento.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ movimiento.producto.nombre }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <Badge :type="movimiento.tipo === 'entrada' ? 'success' : 'danger'" :text="movimiento.tipo" />
            </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ movimiento.cantidad }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatDate(movimiento.created_at) }} </td>
        </tr>
       <tr v-else>
            <td colspan="4" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="movimientos.links" :from="movimientos.from" :to="movimientos.to" :total="movimientos.total" />
</template>

<style scoped>
</style>
