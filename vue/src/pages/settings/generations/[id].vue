<script setup lang="ts">
definePage({
  meta: {
    isBreadcrumb: true,
    isIdRoute: true,
    title: ['actions.view', { entity: 'nouns.generation' }],
  },
})
</script>
<template>
  <div class="generation-page-wrapper">
    <RouterView />
  </div>
</template>
<style scoped></style>
