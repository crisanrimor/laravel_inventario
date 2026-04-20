<script setup>
import { Head } from '@inertiajs/vue3';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import NewAction from '../../Components/Shared/NewAction.vue';
import ListTable from '../../Components/Shared/ListTable.vue';
import ProductoForm from '../../Components/Forms/ProductoForm.vue';
import { useModal } from '../../Composables/useModal';
import Icon from '../../Components/Shared/Icon.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import DeleteForm from '../../Components/Forms/DeleteForm.vue';
import Pagination from '../../Components/Shared/Pagination.vue';
import { formatCurrency } from '../../Utils/currency';
import Badge from '../../Components/UI/Badge.vue';
import { hasPermission } from '../../Utils/permission';

const props = defineProps({ breadcrumb: Array, productos: Object, categorias: Object });

const { open } = useModal()

const create = () => {
    open(ProductoForm, { mode: 'create', categorias: props.categorias }, 'Crear Producto', 'xxl');
}

const edit = producto => {
    open(ProductoForm, { mode: 'edit', data: producto, categorias: props.categorias }, 'Editar Producto');
}

const deleteForm = producto => {
    open(DeleteForm, { data: producto.id, route: 'dashboard.productos.destroy'}, 'Eliminar Producto', 'sm');
}

const getStockStatus = producto => {
    if (producto.stock_actual <= producto.stock_minimo) {
        return { type: 'danger', msg: 'Sin Stock' }
    } else if (producto.stock_actual <= producto.stock_minimo * 1.2) {
        return { type: 'warning', msg: 'Bajo Stock' }
    } else {
        return { type: 'success', msg: 'Normal' }
    }
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Productos" />

    <TitlePage :breadcrumb="breadcrumb" title="Productos"/>

    <NewAction v-if="hasPermission('crear-producto')" @open="create" title="Nuevo Producto" title-button="Crear Producto"/>

    <ListTable title="Lista de Productos" :thead="['Nombre', 'Imagen', 'Código', 'Precio', 'Costo', 'Stock', 'Estado', 'Acciones']">
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
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(producto.precio) }} </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ formatCurrency(producto.costo) }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">{{ producto.stock_actual }}</td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <Badge :type="getStockStatus(producto).type" :text="getStockStatus(producto).msg" />
            </td>
            <td class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">
                <div class="flex justify-center gap-2">
                    <Icon v-if="hasPermission('editar-producto')" @click="edit(producto)" :icon="PencilSquareIcon" />
                    <Icon v-if="hasPermission('eliminar-producto')" @click="deleteForm(producto)" :icon="TrashIcon" />
                </div>
            </td>
        </tr>
       <tr v-else>
            <td colspan="7" class="py-5 text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron registros.</td>
        </tr>
    </ListTable>

    <Pagination :links="productos.links" :from="productos.from" :to="productos.to" :total="productos.total" />
</template>

<style scoped>
</style>
