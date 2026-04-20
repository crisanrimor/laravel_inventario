<script setup>
import { useForm } from '@inertiajs/vue3';
import InputLabel from '../UI/InputLabel.vue';
import { onMounted } from 'vue';
import SelectInput from '../UI/SelectInput.vue';
import InputFile from '../UI/InputFile.vue';

const form = useForm({
    nombre: '',
    descripcion: '',
    sku: '',
    precio: '',
    costo: '',
    stock_minimo: '',
    stock_actual: '',
    categoria_id: '',
    imagen: null
});

const props = defineProps({ mode: String, data: Object, categorias: Object });
const emit = defineEmits(['close']);
const buttonText = props.mode === 'create' ? 'Guardar' : 'Actualizar';
const buttonTextProcess = props.mode === 'create' ? 'Guardando...' : 'Actualizando...'

const submit = () => {
    if(props.mode == 'create'){
        form.post(route('dashboard.productos.store'), {
            onSuccess: () => {
                emit('close');
            }
        });
    }else{
        form.transform(data => ({
            ...data,
            _method: 'put'
        }))
        .post(route('dashboard.productos.update', props.data.id), {
            forceFormData: true,
            onSuccess: () => {
                emit('close');
            }
        });
    }
}

const handleFile = file => {
    if(file.length){
        form.imagen = file[0];
    }
}

onMounted(() => {
    if(props.mode == 'edit'){
        form.nombre = props.data.nombre;
        form.descripcion = props.data.descripcion;
        form.sku = props.data.sku;
        form.precio = props.data.precio;
        form.stock_minimo = props.data.stock_minimo;
        form.categoria_id = props.data.categoria_id;
    }
});
</script>

<template>
    <form @submit.prevent="submit" class="flex flex-col space-y-4">
        <InputLabel label="Nombre" placeholder="Ingresa nombre de producto" name="nombre" :error="form.errors.nombre" v-model="form.nombre"/>
        <InputLabel label="Descripción" placeholder="Ingresa descripción de producto" name="descripcion" :error="form.errors.descripcion" v-model="form.descripcion"/>
        <InputLabel label="SKU" placeholder="Ingresa codigo de producto" name="sku" :error="form.errors.sku" v-model="form.sku"/>

        <div class="flex flex-col md:flex-row gap-3">
            <InputLabel label="Precio" placeholder="Ingresa precio de producto" name="precio" :error="form.errors.precio" v-model="form.precio"/>
            <InputLabel label="Stock Mínimo" placeholder="Ingresa stock minimo de producto" name="stock_minimo" :error="form.errors.stock_minimo" v-model="form.stock_minimo"/>
        </div>

        <div v-if="mode === 'create'" class="flex flex-col md:flex-row gap-3">
            <InputLabel label="Costo" placeholder="Ingresa costo de producto" name="costo" :error="form.errors.costo" v-model="form.costo"/>
            <InputLabel label="Stock Actual" placeholder="Ingresa stock actual de producto" name="stock_actual" :error="form.errors.stock_actual" v-model="form.stock_actual"/>
        </div>

        <SelectInput :data="categorias" label="Categoria" name="categoria_id" :error="form.errors.categoria_id" v-model="form.categoria_id" />

        <InputFile @selected="handleFile" label="Imagen" name="imagen" :error="form.errors.imagen" v-model="form.imagen"/>

        <div v-if="mode === 'edit'" class="mt-2 flex flex-col items-center gap-3">
            <p class="text-light-text-muted dark:text-dark-text-muted text-[14px]">Imagen Actual:</p>
            <figure class="w-25 h-25 rounded-full overflow-hidden border border-slate-200 dark:border-dark-border/5">
                <img :src="data.imagen_url" class="w-full h-full object-cover" :alt="data.nombre">
            </figure>
        </div>

        <button :disabled="form.processing" type="submit" class="text-white text-[14px] cursor-pointer rounded-md px-6 py-2 bg-dark-action-primary hover:brightness-90 dark:hover:bg-dark-action-primary/60 transition-all duration-200">{{ form.processing ? buttonTextProcess : buttonText }}</button>
    </form>
</template>

<style scoped>
</style>
