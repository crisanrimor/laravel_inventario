<script setup>
import { reactive, ref } from 'vue';
import InputLabel from '../UI/InputLabel.vue';

const props = defineProps({ producto: Object, edit: { type: Boolean, default: false } });
const emit = defineEmits(['close', 'action']);

const cantidad = props.edit ? ref(props.producto.cantidad) : ref(1);
const precio = ref(parseFloat(props.producto.precio));
const error = reactive({cantidad: '', precio: ''});

const addProduct = () => {
    error.cantidad = '';
    error.precio = '';

    if(cantidad.value == 0 || cantidad.value === ''){
        error.cantidad = 'La cantidad debe ser mayor a 0.';
    }

    if(cantidad.value > props.producto.stock_actual){
        error.cantidad = 'La cantidad supera al stock actual.';
    }

    if(precio.value == 0 || precio.value === ''){
        error.precio = 'El precio debe ser mayor a 0.';
    }

    if(error.cantidad || error.precio) return;

    emit('action', {id: props.producto.id, nombre: props.producto.nombre, sku:props.producto.sku, cantidad: cantidad.value, stock_actual: props.producto.stock_actual, precio: precio.value});
}
</script>

<template>
    <div class="mb-5">
        <InputLabel label="Cantidad" placeholder="Ingresa cantidad de producto" :error="error.cantidad" v-model="cantidad"/>
        <InputLabel label="Precio" placeholder="Ingresa precio de producto" :error="error.precio" v-model="precio"/>
    </div>

    <button @click="addProduct" class="w-full text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ edit ? 'Cambiar' : 'Agregar' }}</button>
</template>

<style scoped>
</style>
