<script setup>
defineProps({
  icon: {
    type: [Object, Function],
    required: true
  }
});
defineOptions({
  inheritAttrs: false
})
</script>

<template>
  <component :is="icon" v-bind="$attrs" class="w-5 h-5 cursor-pointer" />
</template>
