<script setup lang="ts">
definePage({
  meta: {
    label: 'nouns.teacher',
    title: ['nouns.list', { entity: 'nouns.teacher' }],
    isBreadcrumb: true,
  },
})
</script>

<template>
  <div class="list-teachers">
    <AppBreadcrumbs />
    <RouterView />
  </div>
</template>
<style scoped></style>
