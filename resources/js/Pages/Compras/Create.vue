<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed, reactive } from 'vue';
import { useProductSearch } from '../../Composables/useProductSearch';

import DashboardLayout from '../../Layouts/DashboardLayout.vue';
import TitlePage from '../../Components/Shared/TitlePage.vue';
import Icon from '../../Components/Shared/Icon.vue';
import { CheckCircleIcon, MagnifyingGlassIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';
import Badge from '../../Components/UI/Badge.vue';
import SelectInput from '../../Components/UI/SelectInput.vue';
import InputLabel from '../../Components/UI/InputLabel.vue';
import SearchProduct from '../../Components/Shared/SearchProduct.vue';
import Spinner from '../../Components/UI/Spinner.vue';
import { formatCurrency } from '../../Utils/currency';
import { useModal } from '../../Composables/useModal';
import PedidoCompraForm from '../../Components/Forms/PedidoCompraForm.vue';
import Alerts from '../../Components/UI/Alerts.vue';

const props = defineProps({ breadcrumb: Array, proveedores: Object });
const { open } = useModal();
const { search, results, loading, emptyResult, error: searchError } = useProductSearch();

const form = useForm({
    proveedor_id: '',
    porcentaje_impuesto: '',
    productos: []
});

const error = reactive({
    product: false
});

const subtotal = computed(() => form.productos.reduce((acum, item) => acum + (item.cantidad * item.costo), 0));
const taxes = computed(() => (subtotal.value * Number(form.porcentaje_impuesto || 0)) / 100);
const total = computed(() => subtotal.value + taxes.value);

const changeDataProduct = (producto, edit = false) => {
    open(PedidoCompraForm, { producto, edit }, 'Datos Producto Compra', 'sm', data => {
        if(edit){
            editProduct(data)
        }else{
            addProduct(data)
        }
    });
}

const addProduct = producto => {
    if(!form.productos.some(element => element.id === producto.id)){
        form.productos.push({
            id: producto.id,
            nombre: producto.nombre,
            sku: producto.sku,
            cantidad: producto.cantidad,
            costo: producto.costo
        });
    }
}

const editProduct = producto => {
    const findProduct = form.productos.findIndex(element => element.id === producto.id);
    if(findProduct === -1) return;
    form.productos.splice(findProduct, 1, producto);
}

const removeProduct = id => {
    form.productos = form.productos.filter(element => element.id !== id);
}

const finishBuy = () => {
    error.product = false;

    form.post(route('dashboard.compras.store'), {
        onError: (errors) => {
            Object.keys(errors).forEach(key => {
                const match = key.match(/productos\.(\d+)\.(\w+)/)

                if (match) {
                    error.product = true;
                }
            })
        }
    });
}

defineOptions({
  layout: DashboardLayout
})
</script>

<template>
    <Head title="Crear Compra" />

    <TitlePage :breadcrumb="breadcrumb" title="Crear Compra"/>

    <div class="flex flex-col lg:flex-row gap-8 items-start">
        <section class="w-full lg:w-1/2 xl:w-3/5 flex flex-col space-y-6">
            <div class="flex flex-col space-y-6">
                <div class="flex flex-col">
                    <h3 class="text-2xl font-extrabold text-light-text-body dark:text-dark-text-body tracking-tight uppercase">Busqueda de productos</h3>
                    <p class="text-sm text-light-text-muted dark:text-dark-text-muted font-medium">Utiliza el buscador para agregar productos a la compra.</p>
                </div>
                <div class="relative">
                    <input v-model="search" class="w-full text-[14px] bg-light-card dark:bg-dark-card text-light-text-muted dark:text-dark-text-muted border border-slate-200 dark:border-dark-border/5 p-4 pl-12 rounded-lg outline-none focus:ring-2 focus:ring-dark-action-primary" placeholder="Busca por nombre o código de producto..." type="text"/>
                    <Icon :icon="MagnifyingGlassIcon" class="absolute left-4 top-1/2 -translate-y-1/2 text-light-text-muted dark:text-dark-text-muted" />
                    <div v-show="loading" class="absolute right-4 top-1/2 -translate-y-1/2">
                        <Spinner/>
                    </div>
                </div>

                <div v-if="searchError">
                    <Alerts type="error" :message="searchError" />
                </div>

                <div v-else class="space-y-4">
                    <SearchProduct @add-product="changeDataProduct" v-for="result in results" :producto="result" :added="form.productos" :key="result.id"/>
                    <p v-show="emptyResult" class="text-center text-light-text-muted dark:text-dark-text-muted text-[14px]">No se encontraron productos...</p>
                </div>
            </div>

            <div class="flex flex-col mt-4">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-extrabold text-light-text-body dark:text-dark-text-title tracking-tight uppercase">Resumen del Pedido</h3>
                    <Badge type="primary" :text="form.productos.length + ' items en total'" class="font-bold uppercase" />
                </div>

                <div class="my-2">
                    <small v-if="form.errors.productos" class="text-red-500 tracking-tighter text-[12px]">{{ form.errors.productos }}</small>
                    <small v-if="error.product" class="text-red-500 tracking-tighter text-[12px]">Hay errores en los productos agregados a la compra, revisa que cantidad y costo sean mayor que 0.</small>
                </div>

                <div class="bg-light-card dark:bg-dark-card border border-slate-200 dark:border-dark-border/5 flex-1 overflow-hidden rounded-xl flex flex-col">
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="table-fixed w-full min-w-[600px] text-left border-collapse">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-dark-border/5">
                                    <th class="w-[25%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase truncate">Nombre producto</th>
                                    <th class="w-[20%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase truncate">SKU</th>
                                    <th class="w-[10%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase text-center">Cantidad</th>
                                    <th class="w-[17%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase text-center">Costo Unit.</th>
                                    <th class="w-[13%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase text-right">Subtotal</th>
                                    <th class="w-[15%] px-4 py-3 text-[10px] font-black text-light-text-muted dark:text-dark-text-muted tracking-widest uppercase text-right">Accion</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y text-[12px]">
                                <tr v-if="form.productos.length === 0" class="text-light-text-body dark:text-dark-text-body text-center">
                                    <td colspan="6" class="px-4 py-4">No hay productos agregados.</td>
                                </tr>

                                <tr v-else v-for="producto in form.productos" class="text-light-text-body dark:text-dark-text-body" :key="producto.id">
                                    <td class="px-4 py-4">{{ producto.nombre }}</td>
                                    <td class="px-4 py-4">{{ producto.sku }}</td>
                                    <td class="px-4 py-4 text-center">{{ producto.cantidad }}</td>
                                    <td class="w-25 px-4 py-4 text-center">{{ formatCurrency(producto.costo) }}</td>
                                    <td class="px-4 py-4 text-right">{{ formatCurrency(producto.cantidad * producto.costo) }}</td>
                                    <td class="px-4 py-4 flex justify-end gap-1.5">
                                        <Icon @click="changeDataProduct(producto, true)" :icon="PencilSquareIcon" />
                                        <Icon @click="removeProduct(producto.id)" :icon="TrashIcon" class="text-center"/>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

        <section class="w-full lg:w-1/2 xl:w-2/5 max-w-[500px] mx-auto bg-light-base border border-slate-200 dark:border-none dark:bg-dark-card shadow-light-card p-5 xl:p-8 flex flex-col rounded-xl relative">
            <div class="mb-8">
                <h3 class="text-2xl font-extrabold text-light-text-body dark:text-dark-text-body tracking-tight uppercase mb-4">Finalizar compra</h3>
            </div>
            <div class="space-y-6">
                <SelectInput :data="proveedores" label="Proveedor" :error="form.errors.proveedor_id" v-model="form.proveedor_id"/>
                <InputLabel label="% Impuesto" placeholder="Ingresa % de impuesto" :error="form.errors.porcentaje_impuesto" v-model="form.porcentaje_impuesto"/>
            </div>

            <div class="mt-8 pt-8 border-t border-slate-200 dark:border-dark-border/5 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-light-text-body dark:text-dark-text-body">Subtotal</span>
                    <span class="text-light-text-muted dark:text-dark-text-muted font-semibold">{{formatCurrency(subtotal) }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-light-text-body dark:text-dark-text-body">Taxes ({{ form.porcentaje_impuesto }}%)</span>
                    <span class="text-light-text-muted dark:text-dark-text-muted font-semibold">{{ formatCurrency(taxes) }}</span>
                </div>
                <div class="flex justify-between items-center pt-4">
                    <span class="text-xs uppercase font-black tracking-widest text-light-text-body dark:text-dark-text-body">Total Amount</span>
                    <span class="text-2xl xl:text-3xl font-black text-light-text-body dark:text-dark-text-body">{{ formatCurrency(total) }}</span>
                </div>

                <button v-show="form.productos.length" :disabled="form.processing" @click="finishBuy" class="w-full text-white bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 py-4 mt-10 font-bold text-sm uppercase tracking-widest rounded flex items-center justify-center gap-3 cursor-pointer">
                    <Icon :icon="CheckCircleIcon" class="w-7 h-7"/>
                    {{ form.processing ? 'Finalizando compra...' : 'Confirmar Compra' }}
                </button>
            </div>
        </section>
    </div>
</template>

<style scoped>
</style>
