<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.generation',
    title: ['nouns.list', { entity: 'nouns.generation' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="generations-page-wrapper">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>
