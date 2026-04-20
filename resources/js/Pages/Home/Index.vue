<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import Icon from '../../Components/Shared/Icon.vue';
import { BanknotesIcon, CreditCardIcon, CubeIcon, CurrencyDollarIcon, UsersIcon } from '@heroicons/vue/24/outline';
import CardInfo from '../../Components/Shared/CardInfo.vue';
import { formatCurrency } from '../../Utils/currency';
import Badge from '../../Components/UI/Badge.vue';
import { ref, computed, onMounted } from 'vue';
import VueApexCharts from 'vue3-apexcharts'
import { useDashboardCharts } from '../../Composables/useDashboardCharts';

const props = defineProps({total_clientes: Number, porcentajeVentas: Number, total_ventas_hoy: [Number, String], productosMasVendidos: Object, total_productos: Number, valorInventario: [Number, String], ganancias_totales: Object, inventario_control: Object, clientesFrecuentes: Object})
const { chart1, chart2 } = useDashboardCharts();

const porcentaje = computed(() => {
    const value = Number(props.porcentajeVentas);

    if (!isFinite(value)) return '-';

    const sign = value > 0 ? '+' : value < 0 ? '' : '';
    return `${sign}${value.toFixed(1)}%`;
});

const ventas = computed(() => Object.values(props.ganancias_totales).map(element => element.total) ?? [])
const ganancias = computed(() => Object.values(props.ganancias_totales).map(element => element.ganancias) ?? [])
const total_entradas = computed(() => Object.values(props.inventario_control).map(element => element.total_entradas) ?? [])
const total_salidas = computed(() => Object.values(props.inventario_control).map(element => element.total_salidas) ?? [])

const series = computed(() => [
  {
    name: "Ventas",
    data: ventas.value,
  },
  {
    name: "Ganancias",
    data: ganancias.value,
  },
])

const seriesBar = computed(() => [
    {
        name: 'Entrada Productos',
        data: total_entradas.value
    },
    {
        name: 'Salida Productos',
        data: total_salidas.value
    }
]);

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Panel" />

    <TitlePage title="Panel"/>

    <div class="grid grid-cols-[repeat(auto-fit,minmax(250px,1fr))] gap-4">
        <CardInfo :icon="UsersIcon" type="success" title="Total Clientes" :text="total_clientes" />
        <CardInfo :icon="CubeIcon" type="primary" title="Total Productos" :text="total_productos" />
        <CardInfo :icon="CurrencyDollarIcon" type="warning" title="Valor Inventario" :text="formatCurrency(valorInventario)" />
        <CardInfo :icon="CreditCardIcon" type="violet" title="Total Ventas Hoy">
            <template #content>
                <div class="flex items-center gap-2">
                    <p class="text-[14px] text-light-text-muted dark:text-dark-text-muted">{{ formatCurrency(total_ventas_hoy) }}</p>
                    <Badge :type="porcentajeVentas >= 0 ? 'success' : 'danger'" :text="porcentaje" classBadge="text-[10px]"/>
                </div>
            </template>
        </CardInfo>
    </div>

    <div class="block xl:flex xl:gap-5">
        <div class="w-full xl:w-[60%] mt-5 p-5 bg-light-card dark:bg-dark-card border border-slate-200 dark:border-none rounded-md">
            <p class="text-2xl text-light-text-body dark:text-dark-text-body font-bold tracking-tighter">Reporte Ventas Mensuales</p>
            <VueApexCharts
                type="area"
                height="310"
                :options="chart1"
                :series="series"
            />
        </div>

        <div class="w-full xl:w-[40%] mt-5 p-5 bg-light-card dark:bg-dark-card border border-slate-200 dark:border-none rounded-md">
            <p class="text-2xl text-light-text-body dark:text-dark-text-body font-bold tracking-tighter">Entradas/Salidas Inventario</p>
            <VueApexCharts
                type="bar"
                height="310"
                :options="chart2"
                :series="seriesBar"
            />
        </div>
    </div>

    <div class="block xl:flex gap-5">
        <div class="xl:w-[60%]">
            <ListTable title="Productos Más Vendidos" :thead="['Nombre', 'Imagen', 'Categoria', 'Cantidad']">
                <tr v-if="productosMasVendidos.length" v-for="productos in productosMasVendidos" :key="productos.index">
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ productos.nombre }}</td>
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                        <div class="flex justify-center">
                            <figure class="w-12 h-12 rounded-full overflow-hidden border border-slate-200 dark:border-dark-border/5">
                                <img :src="productos.imagen_url" class="w-full h-full object-cover" :alt="productos.nombre">
                            </figure>
                        </div>
                    </td>
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ productos.categoria.nombre }}</td>
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ productos.movimientos_sum_cantidad }}</td>
                </tr>
                <tr v-else>
                    <td colspan="3" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
                </tr>
            </ListTable>
        </div>

        <div class="xl:w-[40%]">
            <ListTable title="Clientes Frecuentes" :thead="['Nombre', 'Total Ventas']">
                <tr v-if="clientesFrecuentes.length" v-for="cliente in clientesFrecuentes" :key="cliente.index">
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ cliente.cliente.nombre }}</td>
                    <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ cliente.total }}</td>
                </tr>
                <tr v-else>
                    <td colspan="3" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
                </tr>
            </ListTable>
        </div>

    </div>



</template>

<style scoped>
</style>
