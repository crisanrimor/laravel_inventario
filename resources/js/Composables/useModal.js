import { markRaw, ref, shallowRef } from 'vue'

const show = ref(false)
const component = shallowRef(null)
const modalProps = ref({})
const title = ref('')
const defaultSize = ref('lg');
const callback = ref(null);

export function useModal() {
  const open = (comp, props = {}, modalTitle = '', size = 'lg', cb = null) => {
    component.value = markRaw(comp)
    modalProps.value = props
    title.value = modalTitle
    defaultSize.value = size;
    callback.value = cb;
    show.value = true
  }

  const close = () => {
    show.value = false
    component.value = null
    modalProps.value = {}
    title.value = ''
  }

  const action = (payload = null) => {
    close();

    if(callback.value){
        callback.value(payload);
        callback.value = null;
    }
  }

  return {
    show,
    component,
    modalProps,
    title,
    defaultSize,
    open,
    close,
    action
  }
}
