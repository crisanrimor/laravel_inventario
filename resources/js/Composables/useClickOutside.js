import { onMounted, onBeforeUnmount } from 'vue';

export function useClickOutside(elementRef, callback, isActive) {
    const handler = (event) => {
        if (!isActive.value) return;
        if (elementRef.value && !elementRef.value.contains(event.target)) {
            callback(event);
        }
    };

    onMounted(() => {
        document.addEventListener('click', handler);
    });

    onBeforeUnmount(() => {
        document.removeEventListener('click', handler);
    });
}
