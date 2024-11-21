<template>
  <div ref="rootNode" class="example-component">
    <slot />
  </div>
</template>

<script setup lang="ts">
const rootNode = ref<HTMLDivElement | null>(null)

onMounted(() => {
  if (rootNode.value) {
    const topPosition = rootNode.value.getBoundingClientRect().top
    console.log(`Top position of the root node: ${topPosition}px`)
    rootNode.value.style.height =
      (window.innerHeight - topPosition) * 0.9 + 'px'
  }
})
</script>

<style></style>
