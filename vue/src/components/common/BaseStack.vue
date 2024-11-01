<script setup lang="ts">
const props = defineProps<{ direction: 'row' | 'column'; spacing?: number }>()
</script>
<template>
  <div
    :class="{ 'flex-column': direction === 'column' }"
    class="base-stack d-flex"
  >
    <slot />
  </div>
</template>
<style scoped lang="scss">
.base-stack > *:not(:last-child) {
  // add margin right base on spacing prop
}
</style>
