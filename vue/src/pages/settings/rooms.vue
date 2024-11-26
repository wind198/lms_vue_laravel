<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.room',
    title: ['nouns.list', { entity: 'nouns.room' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="rooms-page-wrapper">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>
