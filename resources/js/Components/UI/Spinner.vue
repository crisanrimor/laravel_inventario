<template>
  <div :class="`w-${size} h-${size} border-4 border-gray-300 border-t-blue-500 rounded-full animate-spin`"></div>
</template>

<script setup>
defineProps({
  size: {
    type: String,
    default: '6'
  }
})
</script>
