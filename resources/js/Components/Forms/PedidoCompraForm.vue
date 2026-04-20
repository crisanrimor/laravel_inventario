<script setup>
import { reactive, ref } from 'vue';
import InputLabel from '../UI/InputLabel.vue';

const props = defineProps({ producto: Object, edit: { type: Boolean, default: false } });
const emit = defineEmits(['close', 'action']);

const cantidad = props.edit ? ref(props.producto.cantidad) : ref(1);
const costo = ref(parseFloat(props.producto.costo));
const error = reactive({cantidad: '', costo: ''});

const addProduct = () => {
    error.cantidad = '';
    error.costo = '';

    if(cantidad.value == 0 || cantidad.value === ''){
        error.cantidad = 'La cantidad debe ser mayor a 0.';
    }

    if(costo.value == 0 || costo.value === ''){
        error.costo = 'El costo debe ser mayor a 0.';
    }

    if(error.cantidad || error.costo) return;

    emit('action', {id: props.producto.id, nombre: props.producto.nombre, sku:props.producto.sku, cantidad: cantidad.value, costo: costo.value});
}
</script>

<template>
    <div class="mb-5">
        <InputLabel label="Cantidad" placeholder="Ingresa cantidad de producto" :error="error.cantidad" v-model="cantidad"/>
        <InputLabel label="Costo" placeholder="Ingresa costo de producto" :error="error.costo" v-model="costo"/>
    </div>

    <button @click="addProduct" class="w-full text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ edit ? 'Cambiar' : 'Agregar' }}</button>
</template>

<style scoped>
</style>
