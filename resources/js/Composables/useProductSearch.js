import { ref, watch, onUnmounted } from 'vue';
import axios from 'axios';

export function useProductSearch() {
    const search = ref('');
    const results = ref([]);
    const loading = ref(false);
    const emptyResult = ref(false);

    const error = ref(null);

    let debounceTimer = null
    let abortController = null

    const resetState = () => {
        results.value = [];
        emptyResult.value = false;
        error.value = null;
    };

    watch(search, (value) => {
        clearTimeout(debounceTimer)
        loading.value = true

        debounceTimer = setTimeout(async () => {
            abortController?.abort()
            abortController = new AbortController()

            error.value = null
            emptyResult.value = false;

            if(!value.trim()){
                resetState();
                loading.value = false
                return;
            }

            try {
                const { data } = await axios.post('/api/search',
                    { search: value },
                    { signal: abortController.signal }
                );

                results.value = data.productos

                if(data.productos.length === 0){
                    emptyResult.value = true;
                }
            } catch (e) {
                if (e.name === 'CanceledError') return;
                error.value = 'Ocurrió un error al realizar la búsqueda, intenta nuevamente.'
            } finally {
                loading.value = false
            }
        }, 500)
    });

    onUnmounted(() => {
        clearTimeout(debounceTimer)
        abortController?.abort()
    });

    return {
        search,
        results,
        loading,
        emptyResult,
        error
    }
}
