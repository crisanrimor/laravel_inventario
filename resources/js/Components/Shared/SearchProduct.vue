<script setup>
import { CheckIcon, PlusIcon } from '@heroicons/vue/24/outline';
import Icon from '../../Components/Shared/Icon.vue';
import { formatCurrency } from '../../Utils/currency';
import Badge from '../UI/Badge.vue';
import { computed } from 'vue';

const props = defineProps({ producto: Object, added: Array, mode: { type: String, default: 'compra' } });
const emit = defineEmits(['addProduct']);

const typeBadge = computed(() => {
    if (props.producto.stock_actual <= props.producto.stock_minimo) {
        return 'danger';
    } else if (props.producto.stock_actual <= props.producto.stock_minimo * 1.2) {
        return 'warning';
    } else {
        return 'success';
    }
});

const handleProduct = () => {
    emit('addProduct', props.producto);
}
</script>

<template>
    <div class="bg-light-base shadow-light-card dark:border-none dark:bg-dark-card p-4 flex items-center gap-6 group rounded-lg">
        <div class="w-16 h-16 border-slate-200 dark:border-dark-border/5  rounded overflow-hidden shrink-0 border border-outline-variant/20">
            <img :alt="producto.nombre" class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all" :src="producto.imagen_url"/>
        </div>
        <div class="flex-1">
            <h4 class="font-bold text-light-text-muted dark:text-dark-text-muted text-sm">{{ producto.nombre }}</h4>
            <p class="text-[10px] text-light-text-body dark:text-dark-text-body">SKU: {{ producto.sku }}</p>
            <div class="mt-1 flex gap-4">
                <Badge :type="typeBadge" :text="'Stock actual: ' + props.producto.stock_actual" />
            </div>
        </div>
        <div class="text-right flex flex-col items-end gap-2">
            <span class="text-base font-black text-light-text-body dark:text-dark-text-body">{{ mode === 'compra' ? formatCurrency(producto.costo) : formatCurrency(producto.precio) }}</span>

            <button v-if="added.some(element => element.id === producto.id)" class="flex bg-blue-500 hover:bg-blue-500/80 text-white items-center gap-1 px-3 py-1.5 rounded-sm transition-all text-[11px] font-bold uppercase tracking-tighter cursor-pointer">
                <Icon :icon="CheckIcon"/>
                Añadido
            </button>

            <button v-else @click="handleProduct" class="flex bg-tertiary hover:bg-tertiary/80 text-white items-center gap-1 px-3 py-1.5 rounded-sm transition-all text-[11px] font-bold uppercase tracking-tighter cursor-pointer">
                <Icon :icon="PlusIcon"/>
                Agregar
            </button>
        </div>
    </div>
</template>

<style scoped>
</style>
