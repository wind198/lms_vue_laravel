<script setup lang="ts">
definePage({
  meta: {
    label: 'actions.update',
    title: ['actions.update', { entity: 'nouns.generation' }],
    isBreadcrumb: true,
  },
})
</script>
<template>
  <div class="update-generation-page">
    <CreateGeneration />
  </div>
</template>
<style scoped></style>
