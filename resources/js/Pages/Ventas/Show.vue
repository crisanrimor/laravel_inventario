<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import Pagination from '../../Components/Shared/Pagination.vue';
import { formatCurrency } from '../../Utils/currency';
import CardInfo from '../../Components/Shared/CardInfo.vue';
import { BanknotesIcon, CreditCardIcon, CurrencyDollarIcon, PercentBadgeIcon, UsersIcon } from '@heroicons/vue/24/outline';

const props = defineProps({ breadcrumb: Array, venta: Object, productos: Object, cliente: Object });

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Ver Venta" />

    <TitlePage :breadcrumb="breadcrumb" title="Ver Venta"/>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(200px,1fr))] gap-4">
        <CardInfo :icon="UsersIcon" type="success" title="Cliente" :text="cliente.nombre" />
        <CardInfo :icon="BanknotesIcon" type="primary" title="Subtotal" :text="formatCurrency(venta.subtotal)" />
        <CardInfo :icon="PercentBadgeIcon" type="danger" title="Descuento" :text="formatCurrency(venta.descuento)" />
        <CardInfo :icon="CreditCardIcon" type="warning" title="Impuestos" :text="formatCurrency(venta.impuesto)" />
        <CardInfo :icon="CurrencyDollarIcon" type="violet" title="Total" :text="formatCurrency(venta.total)" />
    </div>

    <ListTable title="Lista de Productos" :thead="['Nombre', 'Imagen', 'Código', 'Precio', 'Cantidad', 'Total']">
        <tr v-if="productos.data.length" v-for="producto in productos.data" :key="producto.index">
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ producto.nombre }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <div class="flex justify-center">
                    <figure class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 dark:border-dark-border/5">
                        <img :src="producto.imagen_url" class="w-full h-full object-cover" :alt="producto.nombre">
                    </figure>
                </div>
            </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ producto.sku }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(producto.pivot.precio) }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ producto.pivot.cantidad }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(producto.pivot.precio * producto.pivot.cantidad) }}</td>
        </tr>
       <tr v-else>
            <td colspan="7" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="productos.links" :from="productos.from" :to="productos.to" :total="productos.total" />
</template>

<style scoped>
</style>
